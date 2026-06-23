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

        $rfmData = $data->map(function ($row) use ($now) {
            return [
                'nama'      => $row->nama_pemesan,
                'recency'   => $now->diffInDays($row->last_order),
                'frequency' => (int) $row->frequency,
                'monetary'  => (float) $row->monetary,
            ];
        })->values()->toArray();

        $recencies   = array_column($rfmData, 'recency');
        $frequencies = array_column($rfmData, 'frequency');
        $monetaries  = array_column($rfmData, 'monetary');

        $normalize = function ($value, $min, $max) {
            return ($max - $min) == 0
                ? 0
                : ($value - $min) / ($max - $min);
        };

        $minR = min($recencies);
        $maxR = max($recencies);

        $minF = min($frequencies);
        $maxF = max($frequencies);

        $minM = min($monetaries);
        $maxM = max($monetaries);

        $samples = [];

        foreach ($rfmData as $row) {
            $samples[] = [
                $normalize($row['recency'], $minR, $maxR),
                $normalize($row['frequency'], $minF, $maxF),
                $normalize($row['monetary'], $minM, $maxM),
            ];
        }

        $kmeans = new KMeans(3);
        $clusters = $kmeans->cluster($samples);

        $result = [];

        foreach ($clusters as $clusterId => $clusterSamples) {
            foreach ($clusterSamples as $sample) {
                $idx = array_search($sample, $samples);

                if ($idx !== false) {
                    $rfmData[$idx]['cluster'] = $clusterId;
                    $result[] = $rfmData[$idx];
                }
            }
        }

        $clusterScores = [];

        foreach ($result as $row) {
            $cid = $row['cluster'];
            $clusterScores[$cid][] =
                $row['frequency'] +
                ($row['monetary'] / 10000);
        }

        $clusterAvg = [];

        foreach ($clusterScores as $cid => $scores) {
            $clusterAvg[$cid] =
                array_sum($scores) / count($scores);
        }

        arsort($clusterAvg);

        $ranking = array_keys($clusterAvg);

        $labels = [
            $ranking[0] => [
                'label' => 'Pelanggan Setia',
                'color' => 'success',
                'badge' => '🟢',
            ],
            $ranking[1] => [
                'label' => 'Pelanggan Potensial',
                'color' => 'warning',
                'badge' => '🟡',
            ],
            $ranking[2] => [
                'label' => 'Pelanggan Jarang',
                'color' => 'danger',
                'badge' => '🔴',
            ],
        ];

        foreach ($result as &$row) {
            $row['label'] = $labels[$row['cluster']]['label'];
            $row['color'] = $labels[$row['cluster']]['color'];
            $row['badge'] = $labels[$row['cluster']]['badge'];
        }

        return $result;
    }

    private function buildChartData(array $pelanggan): array
    {
        $datasets = [];

        $grouped = [
            'Pelanggan Setia' => [],
            'Pelanggan Potensial' => [],
            'Pelanggan Jarang' => [],
        ];

        foreach ($pelanggan as $p) {

            $grouped[$p['label']][] = [
                'x' => $p['recency'],
                'y' => $p['monetary'],
                'nama' => $p['nama'],
                'frequency' => $p['frequency'],
            ];
        }

        $datasets[] = [
            'label' => 'Pelanggan Setia',
            'data' => $grouped['Pelanggan Setia'],
            'backgroundColor' => 'rgba(34,197,94,0.8)',
            'pointRadius' => 8,
        ];

        $datasets[] = [
            'label' => 'Pelanggan Potensial',
            'data' => $grouped['Pelanggan Potensial'],
            'backgroundColor' => 'rgba(234,179,8,0.8)',
            'pointRadius' => 8,
        ];

        $datasets[] = [
            'label' => 'Pelanggan Jarang',
            'data' => $grouped['Pelanggan Jarang'],
            'backgroundColor' => 'rgba(239,68,68,0.8)',
            'pointRadius' => 8,
        ];

        return $datasets;
    }
}