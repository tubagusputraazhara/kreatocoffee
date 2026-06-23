<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Phpml\Clustering\KMeans;

class SegmentasiPelanggan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Segmentasi Pelanggan';
    protected static ?string $navigationGroup = 'Analisis';
    protected static ?string $title = 'Segmentasi Pelanggan (K-Means RFM)';

    protected static string $view = 'filament.pages.segmentasi-pelanggan';

    public array $pelanggan = [];
    public array $chartData = [];

    public function mount(): void
    {
        $this->pelanggan = $this->getSegmentasi();
        $this->chartData = $this->buildChartData($this->pelanggan);
    }

    private function getSegmentasi(): array
    {
        // Ambil data RFM per pelanggan
        $data = DB::table('pemesanan')
            ->where('status', 'lunas')
            ->select(
                'nama_pemesan',
                DB::raw('COUNT(*) as frequency'),
                DB::raw('SUM(total_harga) as monetary'),
                DB::raw('MAX(created_at) as last_order')
            )
            ->groupBy('nama_pemesan')
            ->get();

        if ($data->count() < 3) {
            return [];
        }

        $now = now();

        // Hitung Recency (hari sejak terakhir pesan)
        $rfmData = $data->map(function ($row) use ($now) {
            return [
                'nama'      => $row->nama_pemesan,
                'recency'   => $now->diffInDays($row->last_order),
                'frequency' => (int) $row->frequency,
                'monetary'  => (float) $row->monetary,
            ];
        })->values()->toArray();

        // Normalisasi min-max agar skala seimbang
        $recencies   = array_column($rfmData, 'recency');
        $frequencies = array_column($rfmData, 'frequency');
        $monetaries  = array_column($rfmData, 'monetary');

        $normalize = function (float $value, float $min, float $max): float {
            return $max === $min ? 0 : ($value - $min) / ($max - $min);
        };

        $minR = min($recencies);  $maxR = max($recencies);
        $minF = min($frequencies); $maxF = max($frequencies);
        $minM = min($monetaries);  $maxM = max($monetaries);

        // Buat samples untuk KMeans [R_norm, F_norm, M_norm]
        $samples = [];
        foreach ($rfmData as $row) {
            $samples[] = [
                $normalize($row['recency'],   $minR, $maxR),
                $normalize($row['frequency'], $minF, $maxF),
                $normalize($row['monetary'],  $minM, $maxM),
            ];
        }

        // Jalankan K-Means dengan K=3
        $kmeans = new KMeans(3);
        $clusters = $kmeans->cluster($samples);

        // Gabungkan hasil cluster ke data asli
        $result = [];
        foreach ($clusters as $clusterId => $clusterSamples) {
            foreach ($clusterSamples as $sample) {
                // Cari index sample asli
                $idx = array_search($sample, $samples);
                if ($idx !== false) {
                    $rfmData[$idx]['cluster'] = $clusterId;
                    $result[] = $rfmData[$idx];
                }
            }
        }

        // Tentukan label cluster berdasarkan rata-rata F+M
        $clusterScores = [];
        foreach ($result as $row) {
            $cid = $row['cluster'];
            $clusterScores[$cid][] = $row['frequency'] + ($row['monetary'] / 10000);
        }
        $clusterAvg = [];
        foreach ($clusterScores as $cid => $scores) {
            $clusterAvg[$cid] = array_sum($scores) / count($scores);
        }
        arsort($clusterAvg);
        $ranking = array_keys($clusterAvg);

        $labels = [
            $ranking[0] => ['label' => 'Pelanggan Setia',    'color' => 'success', 'badge' => '🟢'],
            $ranking[1] => ['label' => 'Pelanggan Potensial','color' => 'warning', 'badge' => '🟡'],
            $ranking[2] => ['label' => 'Pelanggan Jarang',   'color' => 'danger',  'badge' => '🔴'],
        ];

        foreach ($result as &$row) {
            $row['label'] = $labels[$row['cluster']]['label']  ?? '-';
            $row['color'] = $labels[$row['cluster']]['color']  ?? 'gray';
            $row['badge'] = $labels[$row['cluster']]['badge']  ?? '';
        }

        return $result;
    }

    private function buildChartData(array $pelanggan): array
    {
        $colorMap = [
            'Pelanggan Setia'     => 'rgba(34,197,94,0.8)',
            'Pelanggan Potensial' => 'rgba(234,179,8,0.8)',
            'Pelanggan Jarang'    => 'rgba(239,68,68,0.8)',
        ];

        $datasets = [];
        $grouped  = [];

        foreach ($pelanggan as $p) {
            $grouped[$p['label']][] = [
                'x' => $p['frequency'],
                'y' => $p['monetary'],
                'nama' => $p['nama'],
            ];
        }

        foreach ($grouped as $label => $points) {
            $datasets[] = [
                'label'           => $label,
                'data'            => $points,
                'backgroundColor' => $colorMap[$label] ?? 'rgba(100,100,100,0.8)',
                'pointRadius'     => 6,
            ];
        }

        return $datasets;
    }
}