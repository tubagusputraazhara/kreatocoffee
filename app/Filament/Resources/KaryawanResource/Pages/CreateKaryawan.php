<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Actions\ScanKtpWithGemini;
use App\Filament\Resources\KaryawanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateKaryawan extends CreateRecord
{
    protected static string $resource = KaryawanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Karyawan berhasil ditambahkan!';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Scan KTP (Opsional)')
                    ->description('Upload foto KTP untuk mengisi form secara otomatis.')
                    ->collapsed()
                    ->schema([
                        FileUpload::make('foto_ktp')
                            ->label('Foto KTP')
                            ->image()
                            ->directory('ktp-temp')
                            ->helperText('Upload KTP lalu klik tombol "Scan KTP".')
                            ->hintAction(
                                Action::make('scanKtp')
                                    ->label('Scan KTP')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->color('info')
                                    ->action(function ($state, $set, $get) {
                                        $file = $get('foto_ktp');

                                        if (!$file) {
                                            Notification::make()
                                                ->title('Upload foto KTP terlebih dahulu.')
                                                ->warning()
                                                ->send();
                                            return;
                                        }

                                        if (is_array($file)) {
                                            $file = array_values($file)[0] ?? null;
                                        }

                                        if (!$file) {
                                            Notification::make()
                                                ->title('File tidak valid.')
                                                ->danger()
                                                ->send();
                                            return;
                                        }

                                        if ($file instanceof TemporaryUploadedFile) {
                                            $path = $file->getRealPath();
                                        } else {
                                            $path = storage_path('app/public/' . $file);
                                        }

                                        if (!file_exists($path)) {
                                            Notification::make()
                                                ->title('File tidak ditemukan.')
                                                ->danger()
                                                ->send();
                                            return;
                                        }

                                        $result = ScanKtpWithGemini::extract($path);

                                        if (empty($result)) {
                                            Notification::make()
                                                ->title('Gagal membaca KTP. Coba foto yang lebih jelas.')
                                                ->danger()
                                                ->send();
                                            return;
                                        }

                                        if (!empty($result['nama'])) {
                                            $set('nama', $result['nama']);
                                        }

                                        if (!empty($result['tanggal_lahir'])) {
                                            $set('tanggal_lahir', $result['tanggal_lahir']);
                                        }

                                        if (!empty($result['jenis_kelamin'])) {
                                            $set('jenis_kelamin', $result['jenis_kelamin']);
                                        }

                                        if (!empty($result['alamat'])) {
                                            $set('alamat', $result['alamat']);
                                        }

                                        Notification::make()
                                            ->title('KTP berhasil discan!')
                                            ->success()
                                            ->send();
                                    })
                            ),
                    ]),

                ...KaryawanResource::form($form)->getComponents(),
            ]);
    }
}