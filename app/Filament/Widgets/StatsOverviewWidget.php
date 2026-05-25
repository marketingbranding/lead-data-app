<?php

namespace App\Filament\Widgets;

use App\Models\Kavling;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    public ?array $pageFilters = null;

    protected function getStats(): array
    {
        $filters = $this->pageFilters ?? [];
        $cabangId = $filters['cabang_id'] ?? null;
        $proyekId = $filters['proyek_id'] ?? null;

        $kavlingQuery = Kavling::query()
            ->when(
                auth()->user()?->hasRole('admin-cabang'),
                fn ($q) => $q->where('cabang_id', auth()->user()->cabang_id),
                fn ($q) => $q->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            )
            ->when($proyekId, fn ($q) => $q->where('proyek_id', $proyekId));

        $totalUnit = (clone $kavlingQuery)->count();
        $unitTersedia = (clone $kavlingQuery)->whereDoesntHave('konsumens')->count();
        $unitTerjual = (clone $kavlingQuery)->whereHas('bast')->count();
        $unitDipesan = $totalUnit - $unitTersedia - $unitTerjual;

        return [
            Stat::make('Total Unit', $totalUnit)
                ->icon('heroicon-o-building-office-2')
                ->description('Jumlah kavling terdaftar')
                ->color('primary'),
            Stat::make('Unit Tersedia', $unitTersedia)
                ->icon('heroicon-o-check-circle')
                ->description('Kavling tanpa konsumen')
                ->color('success'),
            Stat::make('Unit Dipesan', $unitDipesan)
                ->icon('heroicon-o-clock')
                ->description('Kavling dengan konsumen, belum BAST')
                ->color('warning'),
            Stat::make('Unit Terjual', $unitTerjual)
                ->icon('heroicon-o-check-badge')
                ->description('Kavling sudah BAST')
                ->color('danger'),
        ];
    }
}
