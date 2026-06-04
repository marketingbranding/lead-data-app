<?php

namespace App\Filament\Widgets;

use App\Models\Kavling;
use App\Models\Konsumen;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class PipelineFunnelWidget extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected ?string $maxHeight = '250px';

    protected ?string $heading = null;

    public bool $grouped = false;

    public ?array $pageFilters = null;

    public function updatedGrouped(): void
    {
        $this->cachedData = null;
    }

    public function getHeading(): string | Htmlable | null
    {
        $label = $this->grouped ? 'Gabung' : 'Pisah';
        $target = $this->grouped ? 'false' : 'true';

        return new HtmlString(
            'Konsumen per Proses Penjualan' .
            ' <button type="button" wire:click="$set(\'grouped\', ' . $target . ')" ' .
            'class="fi-badge cursor-pointer px-3 py-0.5 text-xs font-medium rounded-full ' .
            'bg-gray-100 hover:bg-gray-200 text-gray-700 ml-3 align-middle">' .
            $label .
            '</button>'
        );
    }

    protected function getData(): array
    {
        $filters = $this->pageFilters ?? [];
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

        $query = Konsumen::whereIn('id_kavling', $kavlingIds);

        $kpr = (clone $query)->where(fn ($q) => $q->where('status_cash', '!=', 'YA')->orWhereNull('status_cash'));
        $cash = (clone $query)->where('status_cash', 'YA');

        $labels = ['Konsumen', 'Bi Checking', 'PSJB', 'Pemberkasan', 'Proses Bank', 'PPJB Dev', 'Akad', 'BAST'];

        $kprData = [
            (clone $kpr)->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('biChecking'))->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('psjb'))->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('pemberkasan'))->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('prosesBank'))->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('ppjbDev'))->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('akad'))->count(),
            (clone $kpr)->whereHas('kavling', fn ($q) => $q->has('bast'))->count(),
        ];

        $cashData = [
            (clone $cash)->count(),
            0,
            0,
            0,
            0,
            (clone $cash)->whereHas('kavling', fn ($q) => $q->has('ppjbDev'))->count(),
            (clone $cash)->whereHas('kavling', fn ($q) => $q->has('akad'))->count(),
            (clone $cash)->whereHas('kavling', fn ($q) => $q->has('bast'))->count(),
        ];

        if ($this->grouped) {
            return [
                'datasets' => [
                    [
                        'label' => 'Total',
                        'data' => array_map(fn ($k, $c) => $k + $c, $kprData, $cashData),
                        'backgroundColor' => '#3b82f6',
                        'borderColor' => '#2563eb',
                    ],
                ],
                'labels' => $labels,
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'KPR',
                    'data' => $kprData,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                ],
                [
                    'label' => 'CASH',
                    'data' => $cashData,
                    'backgroundColor' => '#14b8a6',
                    'borderColor' => '#0d9488',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => ! $this->grouped,
                    'position' => 'top',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
