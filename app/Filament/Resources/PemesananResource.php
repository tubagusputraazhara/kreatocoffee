<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananResource\Pages;
use App\Models\Pemesanan;
use App\Models\Pelanggan;
use App\Models\Menu;
use App\Services\JurnalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PemesananResource extends Resource
{
    protected static ?string $model = Pemesanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Data Pemesanan')
                ->description('Informasi pelanggan, meja, dan kode pesanan')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    // ✅ Grid 4 kolom: tambahan field jenis_pemesanan dari teman
                    Grid::make(4)->schema([
                        TextInput::make('kode_pemesanan')
                            ->label('Kode Pemesanan')
                            ->default(fn () => Pemesanan::generateKode())
                            ->readOnly()
                            ->required(),

                        Select::make('nama_pemesan')
                            ->label('Pelanggan')
                            ->options(
                                Pelanggan::all()->mapWithKeys(
                                    fn ($p) => [$p->nama_pelanggan => '#' . $p->id . ' - ' . $p->nama_pelanggan]
                                )
                            )
                            ->searchable()
                            ->required(),

                        // ✅ Tambahan dari teman: jenis pemesanan (Dine In / Take Away)
                        Select::make('jenis_pemesanan')
                            ->label('Jenis Pemesanan')
                            ->options([
                                'dine_in'   => 'Dine In',
                                'take_away' => 'Take Away',
                            ])
                            ->default('dine_in')
                            ->reactive()
                            ->required(),

                        // ✅ no_meja sekarang conditional: hanya tampil jika bukan take_away
                        TextInput::make('no_meja')
                            ->label('No Meja')
                            ->visible(fn (Get $get) => $get('jenis_pemesanan') !== 'take_away')
                            ->required(fn (Get $get) => $get('jenis_pemesanan') !== 'take_away'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('no_wa')
                            ->label('No. WhatsApp')
                            ->tel()
                            ->nullable(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->nullable(),
                    ]),
                ])
                ->collapsible(),

            Section::make('Item Pesanan')
                ->description('Tambahkan menu yang dipesan, harga dan total dihitung otomatis')
                ->icon('heroicon-o-shopping-cart')
                ->schema([
                    Repeater::make('details')
                        ->relationship('details')
                        ->label('')
                        ->schema([
                            Grid::make(12)->schema([
                                Select::make('id_menu')
                                    ->label('Menu')
                                    ->options(Menu::all()->pluck('nama_menu', 'id_menu'))
                                    ->searchable()
                                    ->reactive()
                                    ->columnSpan(5)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $item = Menu::find($state);
                                        if ($item) {
                                            $set('nama_menu', $item->nama_menu);
                                            $set('harga_satuan', $item->harga);
                                        }
                                    })
                                    ->required(),

                                TextInput::make('nama_menu')
                                    ->hidden()
                                    ->dehydrated(),

                                TextInput::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->reactive()
                                    ->columnSpan(3)
                                    ->afterStateUpdated(
                                        fn (Get $get, Set $set) => $set('subtotal', (float) $get('harga_satuan') * (int) ($get('qty') ?? 1))
                                    ),

                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->columnSpan(2)
                                    ->afterStateUpdated(
                                        fn (Get $get, Set $set) => $set('subtotal', (float) $get('harga_satuan') * (int) ($get('qty') ?? 1))
                                    )
                                    ->required(),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->readOnly()
                                    ->columnSpan(2),
                            ]),
                        ])
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            $items = $get('details') ?? [];
                            $total = collect($items)->sum(fn ($i) => (float) ($i['subtotal'] ?? 0));
                            $set('total_harga', $total);
                        })
                        ->deleteAction(
                            fn ($action) => $action->after(function (Get $get, Set $set) {
                                $items = $get('details') ?? [];
                                $total = collect($items)->sum(fn ($i) => (float) ($i['subtotal'] ?? 0));
                                $set('total_harga', $total);
                            })
                        )
                        ->addActionLabel('+ Tambah Item')
                        ->collapsible()
                        ->defaultItems(1)
                        ->required(),
                ]),

            Section::make('Status')
                ->icon('heroicon-o-credit-card')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly()
                            ->required(),

                        // ✅ Status pembayaran (versi main — nilai belum_lunas/lunas/batal dipertahankan)
                        Select::make('status')
                            ->label('Status Pembayaran')
                            ->options([
                                'belum_lunas' => 'Belum Lunas',
                                'lunas'       => 'Lunas',
                                'batal'       => 'Batal',
                            ])
                            ->default('belum_lunas')
                            ->required(),

                        // ✅ Status pesanan (versi main — field name status_pesanan dipertahankan)
                        Select::make('status_pesanan')
                            ->label('Status Pesanan')
                            ->options([
                                'diproses'   => 'Sedang Dimasak',
                                'diantarkan' => 'Sudah Diantarkan',
                                'selesai'    => 'Selesai',
                            ])
                            ->default('diproses'),
                    ]),

                    Textarea::make('catatan')
                        ->label('Catatan')
                        ->helperText('Metode pembayaran (CASH/QRIS) bisa dituliskan di sini')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pemesanan')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('kode_pemesanan')
                    ->label('Kode')
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-hashtag')
                    ->searchable(),

                // ✅ Tambahan dari teman: kolom jenis pemesanan (Dine In / Take Away)
                TextColumn::make('jenis_pemesanan')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'take_away' ? 'Take Away' : 'Dine In')
                    ->icon(fn ($state) => $state === 'take_away'
                        ? 'heroicon-o-shopping-bag'
                        : 'heroicon-o-building-storefront')
                    ->color(fn ($state) => $state === 'take_away' ? 'warning' : 'info'),

                // ✅ no_meja handle kasus take_away (tambahan dari teman)
                TextColumn::make('no_meja')
                    ->label('No Meja')
                    ->getStateUsing(fn ($record) => $record->jenis_pemesanan === 'take_away'
                        ? '-'
                        : ($record->no_meja ?? '-'))
                    ->sortable(),

                TextColumn::make('nama_pemesan')
                    ->label('Pelanggan')
                    ->icon('heroicon-o-user')
                    ->searchable(),

                TextColumn::make('details_summary')
                    ->label('Item Pesanan')
                    ->wrap()
                    ->getStateUsing(function ($record) {
                        if ($record->details && $record->details->count() > 0) {
                            return $record->details
                                ->map(fn ($d) => '• ' . $d->nama_menu . ' ×' . $d->qty)
                                ->implode("\n");
                        }
                        return '-';
                    })
                    ->html()
                    ->formatStateUsing(fn ($state) => str($state)->replace("\n", '<br>')),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('idr')
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),

                // ✅ Status pembayaran (nilai main dipertahankan, icon dari teman ditambahkan)
                TextColumn::make('status')
                    ->label('Pembayaran')
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'belum_lunas' => 'heroicon-o-clock',
                        'lunas'       => 'heroicon-o-banknotes',
                        'batal'       => 'heroicon-o-x-circle',
                        default       => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'belum_lunas' => 'warning',
                        'lunas'       => 'success',
                        'batal'       => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'belum_lunas' => 'Belum Lunas',
                        'lunas'       => 'Lunas',
                        'batal'       => 'Batal',
                        default       => $state,
                    }),

                // ✅ Status pesanan (field name status_pesanan dipertahankan, icon dari teman ditambahkan)
                TextColumn::make('status_pesanan')
                    ->label('Status Pesanan')
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'diproses'   => 'heroicon-o-fire',
                        'diantarkan' => 'heroicon-o-truck',
                        'selesai'    => 'heroicon-o-check-circle',
                        default      => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'diproses'   => 'warning',
                        'diantarkan' => 'info',
                        'selesai'    => 'success',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'diproses'   => 'Sedang Dimasak',
                        'diantarkan' => 'Sudah Diantarkan',
                        'selesai'    => 'Selesai',
                        default      => $state,
                    }),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(40)
                    ->color('gray')
                    ->size('sm'),

                TextColumn::make('sumber')
                    ->label('Sumber')
                    ->badge()
                    ->icon(fn ($state) => $state === 'customer' ? 'heroicon-o-device-phone-mobile' : 'heroicon-o-computer-desktop')
                    ->color(fn ($state) => $state === 'customer' ? 'info' : 'gray'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since()
                    ->color('gray')
                    ->size('sm'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->recordClasses(fn ($record) => match ($record->status) {
                'lunas'       => 'border-l-4 border-success-500',
                'batal'       => 'border-l-4 border-danger-500',
                default       => 'border-l-4 border-warning-500',
            })
            ->headerActions([
                Tables\Actions\Action::make('kasir')
                    ->label('Buka Kasir')
                    ->icon('heroicon-o-calculator')
                    ->color('primary')
                    ->url(url('/kasir')),

                Tables\Actions\CreateAction::make()
                    ->label('Pemesanan Baru')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum_lunas' => 'Belum Lunas',
                        'lunas'       => 'Lunas',
                        'batal'       => 'Batal',
                    ]),

                Tables\Filters\SelectFilter::make('status_pesanan')
                    ->label('Status Pesanan')
                    ->options([
                        'diproses'   => 'Sedang Dimasak',
                        'diantarkan' => 'Sudah Diantarkan',
                        'selesai'    => 'Selesai',
                    ]),

                // ✅ Tambahan dari teman: filter jenis pemesanan
                Tables\Filters\SelectFilter::make('jenis_pemesanan')
                    ->label('Jenis Pemesanan')
                    ->options([
                        'dine_in'   => 'Dine In',
                        'take_away' => 'Take Away',
                    ]),

                Tables\Filters\SelectFilter::make('sumber')
                    ->label('Sumber')
                    ->options([
                        'kasir'    => 'Kasir',
                        'customer' => 'Customer',
                    ]),
            ])
            ->actions([
                // ✅ Tandai Lunas Cash (versi main dipertahankan)
                Tables\Actions\Action::make('lunas_cash')
                    ->label('Tandai Lunas (Cash)')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'belum_lunas')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran Cash')
                    ->modalDescription('Pastikan pembayaran cash sudah diterima sebelum mengkonfirmasi.')
                    ->action(function ($record) {
                        $record->update(['status' => 'lunas']);
                        if (!$record->jurnal_dibuat) {
                            JurnalService::jurnalPenjualan($record);
                            $record->update(['jurnal_dibuat' => true]);
                        }
                    }),

                // ✅ Tambahan dari teman: ActionGroup + Filament Notification
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('diantarkan')
                        ->label('Sudah Diantarkan')
                        ->icon('heroicon-o-truck')
                        ->color('info')
                        ->visible(fn ($record) => $record->status_pesanan === 'diproses')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-truck')
                        ->modalHeading('Konfirmasi Pesanan Telah Diantar')
                        ->modalDescription('Pastikan pesanan sudah diantarkan sebelum mengkonfirmasi.')
                        ->modalSubmitActionLabel('Ya, Sudah Diantarkan')
                        ->action(function ($record) {
                            $record->update(['status_pesanan' => 'diantarkan']);

                            \Filament\Notifications\Notification::make()
                                ->title('Pesanan sedang diantarkan')
                                ->info()
                                ->send();
                        }),

                    Tables\Actions\Action::make('selesai_pesanan')
                        ->label('Selesai')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => $record->status_pesanan === 'diantarkan')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-check-circle')
                        ->modalHeading('Selesaikan Pesanan Ini?')
                        ->modalSubmitActionLabel('Ya, Selesai')
                        ->action(function ($record) {
                            $record->update(['status_pesanan' => 'selesai']);

                            \Filament\Notifications\Notification::make()
                                ->title('Pesanan selesai')
                                ->success()
                                ->send();
                        }),
                ])
                    ->label('Proses Pesanan')
                    ->icon('heroicon-o-fire')
                    ->color('info')
                    ->button()
                    ->visible(fn ($record) => $record->status_pesanan !== 'selesai'),

                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPemesanans::route('/'),
            'create' => Pages\CreatePemesanan::route('/create'),
            'edit'   => Pages\EditPemesanan::route('/{record}/edit'),
        ];
    }
}