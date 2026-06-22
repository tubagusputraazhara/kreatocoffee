<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemesananResource\Pages;
use App\Models\Pemesanan;
use App\Models\Pelanggan;
use App\Models\Menu;
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
use Filament\Tables\Columns\Layout\Stack;

class PemesananResource extends Resource
{
    protected static ?string $model = Pemesanan::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pemesanan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pemesanan')
                    ->description('Informasi pelanggan, meja, dan kode pesanan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Grid::make(3)->schema([
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

                            TextInput::make('no_meja')
                                ->label('No Meja')
                                ->required(),
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
                        Grid::make(2)->schema([
                            TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->numeric()
                                ->prefix('Rp')
                                ->readOnly()
                                ->required(),

                            Select::make('status')
                                ->label('Status Pemesanan')
                                ->options([
                                    'pending' => 'Pending',
                                    'selesai' => 'Selesai',
                                    'batal'   => 'Batal',
                                ])
                                ->default('pending')
                                ->required(),
                        ]),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->helperText('Metode pembayaran (CASH/QRIS) bisa dituliskan di sini, misal: [CASH] tanpa kembalian')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('kode_pemesanan')
                        ->label('Kode')
                        ->weight('bold')
                        ->color('primary')
                        ->icon('heroicon-o-hashtag')
                        ->searchable(),

                    TextColumn::make('nama_pemesan')
                        ->label('Pelanggan')
                        ->icon('heroicon-o-user')
                        ->color('gray')
                        ->searchable(),

                    TextColumn::make('no_meja')
                        ->label('Meja')
                        ->formatStateUsing(fn ($state) => 'Meja ' . $state)
                        ->icon('heroicon-o-map-pin')
                        ->size('sm')
                        ->color('gray'),
                ])->space(2),

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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'selesai' => 'heroicon-o-check-circle',
                        'batal'   => 'heroicon-o-x-circle',
                        default   => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'selesai' => 'success',
                        'batal'   => 'danger',
                        default   => 'gray',
                    }),

                TextColumn::make('catatan')
                    ->label('Metode / Catatan')
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
                'selesai' => 'border-l-4 border-success-500',
                'batal'   => 'border-l-4 border-danger-500',
                default   => 'border-l-4 border-warning-500',
            })
            ->headerActions([
                Tables\Actions\Action::make('kasir')
                    ->label('Buka Kasir')
                    ->icon('heroicon-o-calculator')
                    ->color('gray')
                    ->url(url('/kasir')),

                Tables\Actions\CreateAction::make()
                    ->label('Pemesanan Baru')
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'selesai' => 'Selesai',
                        'batal'   => 'Batal',
                    ]),

                Tables\Filters\SelectFilter::make('sumber')
                    ->label('Sumber')
                    ->options([
                        'kasir'    => 'Kasir',
                        'customer' => 'Customer',
                    ]),
            ])
            ->actions([
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
//ubah tampilan dan nambahin sesuatu