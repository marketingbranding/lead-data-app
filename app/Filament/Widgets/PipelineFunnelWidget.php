<?php

namespace App\Filament\Widgets;

use App\Models\Kavling;
use App\Models\Konsumen;
use Filament\Widgets\ChartWidget;

class PipelineFunnelWidget extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '250px';

    protected ?string $heading = 'Konsumen per Proses Penjualan';

    protected function getData(): array
    {
        $filters = session('dashboard_filters', []);
        $cabangId = $filters['cabang_id'] ?? null;
        $proyekId = $filters['proyek_id'] ?? null;

        $kavlingIds = Kavling::query()
            ->when(
                auth()->user()?->hasRole('admin-cabang'),
                fn ($q) => $q->where('cabang_id', auth()->user()->cabang_id),
                fn ($q) => $q->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            )
            ->when($proyekId, fn ($q) => $q->where('proyek_id', $proyekId))
            ->pluck('id_kavling');

        $stages = [
            'Konsumen' => Konsumen::whereIn('id_kavling', $kavlingIds)->count(),
            'Bi Checking' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('biChecking'))->count(),
            'PSJB' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('psjb'))->count(),
            'Pemberkasan' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('pemberkasan'))->count(),
            'Proses Bank' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('prosesBank'))->count(),
            'PPJB Dev' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('ppjbDev'))->count(),
            'Akad' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('akad'))->count(),
            'BAST' => Konsumen::whereIn('id_kavling', $kavlingIds)->whereHas('kavling', fn($q) => $q->has('bast'))->count(),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Konsumen',
                    'data' => array_values($stages),
                ],
            ],
            'labels' => array_keys($stages),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
