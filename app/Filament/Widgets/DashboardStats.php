<?php

namespace App\Filament\Widgets;

use App\Models\Pemesanan;
use App\Models\BiayaOperasional;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $totalPembeli = Pemesanan::distinct('nama_pemesan')->count('nama_pemesan');

        $totalTransaksi = Pemesanan::where('status', 'lunas')->count();

        $totalPenjualan = Pemesanan::where('status', 'lunas')->sum('total_harga');

        $totalBiaya = BiayaOperasional::sum('jumlah_biaya');

        $totalKeuntungan = $totalPenjualan - $totalBiaya;

        return [
            Stat::make('Total Pembeli', $totalPembeli)
                ->description('Jumlah pelanggan unik')
                ->color('info'),

            Stat::make('Total Transaksi', $totalTransaksi)
                ->description('Pesanan yang sudah lunas')
                ->color('warning'),

            Stat::make('Total Penjualan', 'Rp ' . number_format($totalPenjualan, 0, ',', '.'))
                ->description('Total pemasukan dari pesanan lunas')
                ->color('success'),

            Stat::make('Total Keuntungan', 'Rp ' . number_format($totalKeuntungan, 0, ',', '.'))
                ->description('Penjualan - biaya operasional')
                ->color('danger'),
        ];
    }
}