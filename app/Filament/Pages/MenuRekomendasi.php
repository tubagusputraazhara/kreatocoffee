<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Phpml\Association\Apriori;

class MenuRekomendasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string $navigationLabel = 'Rekomendasi Menu';
    protected static ?string $navigationGroup = 'Analisis';
    protected static ?string $title = 'Rekomendasi Menu (Apriori)';

    protected static string $view = 'filament.pages.menu-rekomendasi';

    public array $rules = [];

    public function mount(): void
    {
        $this->rules = $this->getAprioriRules();
    }

    private function getAprioriRules(): array
    {
        // ✅ 1. Ambil histori transaksi dari detail_pemesanan
        $transaksi = DB::table('detail_pemesanan')
            ->join('pemesanan', 'detail_pemesanan.id_pemesanan', '=', 'pemesanan.id_pemesanan')
            ->where('pemesanan.status', 'lunas') // hanya transaksi lunas
            ->select('detail_pemesanan.id_pemesanan', 'detail_pemesanan.nama_menu')
            ->get();

        // ✅ 2. Bentuk dataset per transaksi
        $dataset = [];
        foreach ($transaksi as $t) {
            $dataset[$t->id_pemesanan][] = $t->nama_menu;
        }
        $dataset = array_values($dataset);

        // ✅ Cek kalau data kosong
        if (empty($dataset)) {
            return [];
        }

        // ✅ 3. Jalankan Apriori
        $minSupport    = 0.1; // turunkan dari 0.2 ke 0.1
        $minConfidence = 0.3; // turunkan dari 0.5 ke 0.3

        $associator = new Apriori($minSupport, $minConfidence);
        $associator->train($dataset, []);

        return $associator->getRules();
    }
}