<x-filament-panels::page>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="fi-section rounded-xl bg-white dark:bg-gray-900 p-4 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Karyawan</p>
            <p class="text-2xl font-bold text-gray-950 dark:text-white mt-1">{{ $this->getTotalKaryawan() }}</p>
        </div>

        <div class="fi-section rounded-xl bg-white dark:bg-gray-900 p-4 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Gaji Bersih</p>
            <p class="text-2xl font-bold text-gray-950 dark:text-white mt-1">{{ $this->getTotalGajiBersih() }}</p>
        </div>

        <div class="fi-section rounded-xl bg-white dark:bg-gray-900 p-4 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            <p class="text-sm text-gray-500 dark:text-gray-400">Sudah Dibayar</p>
            <p class="text-2xl font-bold text-gray-950 dark:text-white mt-1">{{ $this->getTotalSudahDibayar() }}</p>
        </div>
    </div>

    {{-- Tabel Laporan --}}
    {{ $this->table }}

</x-filament-panels::page>