<?php

namespace App\Filament\Pages;

use App\Models\Cabang;
use App\Models\Proyek;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard';

    public function getColumns(): int | array
    {
        return 3;
    }

    public function getWidgetsContentComponent(): Component
    {
        $filterHash = md5(json_encode($this->filters ?? []));

        return Grid::make($this->getColumns())
            ->schema(fn (): array => $this->getWidgetsSchemaComponents($this->getWidgets()))
            ->key("widgets-{$filterHash}");
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('cabang_id')
                    ->label('Cabang')
                    ->placeholder('Semua Cabang')
                    ->options(Cabang::pluck('nama', 'id'))
                    ->hidden(fn () => auth()->user()?->hasRole('admin-cabang'))
                    ->live()
                    ->afterStateUpdated(fn ($state, $set) => $set('proyek_id', null)),
                Select::make('proyek_id')
                    ->label('Proyek')
                    ->placeholder('Semua Proyek')
                    ->options(fn ($get) => Proyek::query()
                        ->when(
                            $get('cabang_id'),
                            fn ($q, $v) => $q->where('cabang_id', $v)
                        )
                        ->when(
                            auth()->user()?->hasRole('admin-cabang'),
                            fn ($q) => $q->where('cabang_id', auth()->user()->cabang_id)
                        )
                        ->pluck('nama_proyek', 'id')
                    )
                    ->live(),
            ]);
    }
}
