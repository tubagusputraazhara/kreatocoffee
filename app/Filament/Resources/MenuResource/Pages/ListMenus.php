<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// Tambahan
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use App\Filament\Exports\MenuExporter;
use App\Models\Menu;
use App\Models\MarketTrend;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use App\Filament\Resources\MenuResource\Widgets\TrendMenuChart;
use App\Filament\Resources\MenuResource\Widgets\TrendKategoriChart;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // 1. Tombol export excel (Posisi Atas)
            ExportAction::make()
                ->label('Export Menu')
                ->exporter(MenuExporter::class)
                ->color('success'),

            // 2. Tombol unduh PDF (Posisi Atas)
            Action::make('downloadPdf')
                ->label('Unduh PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $menus = Menu::all();
                    $pdf = Pdf::loadView('pdf.menu', ['menus' => $menus]);
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'daftar-menu.pdf'
                    );
                }),

            // 3. Tombol Refresh AI Insights (menganalisis tren menu lewat Gemini AI)
            Action::make('refreshAiInsights')
                ->label('Refresh AI Insights')
                ->icon('heroicon-m-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Update Analisis Tren')
                ->modalDescription('Sistem akan menghubungi Gemini AI untuk menganalisis tren menu kafe terbaru berdasarkan data menu yang ada. Lanjutkan?')
                ->action(function () {
                    $apiKey = env('GEMINI_API_KEY');

                    if (empty($apiKey)) {
                        Notification::make()
                            ->title('GEMINI_API_KEY belum diatur di .env')
                            ->danger()
                            ->send();
                        return;
                    }

                    $daftarMenu = Menu::all(['nama_menu', 'kategori'])
                        ->map(fn ($m) => "{$m->nama_menu} ({$m->kategori})")
                        ->implode(', ');

                    $tahun = now()->year;

                    $prompt = <<<PROMPT
                        Kamu adalah analis tren bisnis kafe. Berikut daftar menu yang dijual oleh kafe kami saat ini: {$daftarMenu}.

                        Berdasarkan tren kuliner dan kedai kopi tahun {$tahun} di Indonesia, berikan analisis singkat dalam format JSON SAJA tanpa markdown, tanpa backtick, tanpa penjelasan tambahan, dengan struktur persis seperti ini:

                        {
                          "nama_tren": "judul singkat tren, contoh: Tren Menu Kopi Susu {$tahun}",
                          "analisis_ai": "analisis naratif singkat 3-5 kalimat tentang tren rasa/menu kafe saat ini dan relevansinya dengan menu yang dijual",
                          "menu_populer": "daftar 3-5 nama menu/rasa yang sedang tren, dipisah koma",
                          "kategori_terlaris": "daftar 2-3 kategori menu yang diprediksi paling laris, dipisah koma"
                        }
                        PROMPT;

                    try {
                        // Coba ulang otomatis sampai 3x dengan jeda 2 detik, khusus untuk error 503
                        // (Gemini API server sedang sibuk/overload, bukan masalah di kode kita)
                        $response = retry(3, function () use ($apiKey, $prompt) {
                            $resp = Http::timeout(30)->post(
                                "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                                [
                                    'contents' => [
                                        ['parts' => [['text' => $prompt]]],
                                    ],
                                ]
                            );

                            // Lempar exception khusus untuk 503 supaya retry() mengulang;
                            // status lain langsung diteruskan tanpa diulang.
                            if ($resp->status() === 503) {
                                throw new \Exception('Gemini API sedang sibuk (503), mencoba ulang...');
                            }

                            return $resp;
                        }, fn ($attempt) => $attempt * 2000); // jeda makin lama: 2s, 4s, 6s

                        if (!$response->successful()) {
                            $statusCode = $response->status();
                            $pesanError = $statusCode === 503
                                ? 'Server Gemini sedang sibuk (503). Sudah dicoba ulang otomatis 3x, silakan coba lagi sebentar lagi.'
                                : "Gemini API merespons dengan status {$statusCode}";

                            throw new \Exception($pesanError);
                        }

                        $teks = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;

                        if (!$teks) {
                            throw new \Exception('Respons Gemini tidak berisi teks analisis.');
                        }

                        // Bersihkan kemungkinan markdown code fence yang masih terbawa
                        $teksBersih = trim(preg_replace('/```json|```/', '', $teks));
                        $data       = json_decode($teksBersih, true);

                        if (!$data) {
                            throw new \Exception('Gagal mem-parsing respons JSON dari Gemini.');
                        }

                        MarketTrend::create([
                            'nama_tren'         => $data['nama_tren'] ?? "Tren Menu Kafe {$tahun}",
                            'analisis_ai'       => $data['analisis_ai'] ?? '-',
                            'menu_populer'      => $data['menu_populer'] ?? null,
                            'kategori_terlaris' => $data['kategori_terlaris'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Analisis tren berhasil diperbarui')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        report($e);

                        Notification::make()
                            ->title('Gagal memperbarui analisis tren')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    // Pastikan widget terdaftar di sini agar muncul di atas tabel
    protected function getHeaderWidgets(): array
    {
        return [
            TrendMenuChart::class,
            TrendKategoriChart::class,
        ];
    }
}