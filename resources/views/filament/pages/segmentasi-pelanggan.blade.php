<x-filament-panels::page>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @php
            $counts = collect($pelanggan)->countBy('label');
        @endphp

        @foreach ([
            ['label' => 'Pelanggan Setia',     'emoji' => '🟢', 'color' => 'border-green-400'],
            ['label' => 'Pelanggan Potensial', 'emoji' => '🟡', 'color' => 'border-yellow-400'],
            ['label' => 'Pelanggan Jarang',    'emoji' => '🔴', 'color' => 'border-red-400'],
        ] as $card)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow border-l-4 {{ $card['color'] }} p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $card['emoji'] }} {{ $card['label'] }}</p>
                <p class="text-3xl font-bold mt-1">{{ $counts[$card['label']] ?? 0 }}</p>
                <p class="text-xs text-gray-400 mt-1">pelanggan</p>
            </div>
        @endforeach
    </div>

    @if(empty($pelanggan))
        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-300 rounded-xl p-6 text-center">
            <p class="text-yellow-700 dark:text-yellow-200 font-medium">
                ⚠️ Data belum cukup. Butuh minimal 3 pelanggan dengan status <strong>lunas</strong> untuk menjalankan K-Means.
            </p>
        </div>
    @else

        {{-- Scatter Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-1">Scatter Plot: Frekuensi vs Total Belanja</h2>
            <p class="text-xs text-gray-400 mb-4">Setiap titik mewakili satu pelanggan. Warna menunjukkan segmen.</p>
            <div style="height: 320px; position: relative;">
                <canvas id="scatterChart"></canvas>
            </div>
        </div>

        {{-- Tabel Pelanggan --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-700 dark:text-gray-200">Detail Pelanggan per Segmen</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 text-left">Nama Pemesan</th>
                            <th class="px-4 py-3 text-center">Recency (hari)</th>
                            <th class="px-4 py-3 text-center">Frequency</th>
                            <th class="px-4 py-3 text-right">Monetary</th>
                            <th class="px-4 py-3 text-center">Segmen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach(collect($pelanggan)->sortBy('label') as $p)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                                    {{ $p['nama'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">
                                    {{ $p['recency'] }} hari lalu
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">
                                    {{ $p['frequency'] }}x
                                </td>
                                <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">
                                    Rp {{ number_format($p['monetary'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                        @if($p['color'] === 'success') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                                        @elseif($p['color'] === 'warning') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                                        @else bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                                        @endif">
                                        {{ $p['badge'] }} {{ $p['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif

    {{-- Chart.js Script --}}
    @if(!empty($pelanggan))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('scatterChart').getContext('2d');
            const datasets = @json($chartData);

            new Chart(ctx, {
                type: 'scatter',
                data: { datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const p = context.raw;
                                    return `${p.nama} — ${context.dataset.label} | Freq: ${p.x}x | Rp ${p.y.toLocaleString('id-ID')}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: { display: true, text: 'Frekuensi Order' },
                            ticks: { stepSize: 1 }
                        },
                        y: {
                            title: { display: true, text: 'Total Belanja (Rp)' },
                            ticks: {
                                callback: v => 'Rp ' + v.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endif

</x-filament-panels::page>