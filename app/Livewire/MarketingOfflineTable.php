<?php

namespace App\Livewire;

use App\Models\Campaign;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Contracts\TranslatableContentDriver;
use Livewire\Component;

class MarketingOfflineTable extends Component implements HasSchemas, HasTable
{
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    {
        return null;
    }

    public function bootedInteractsWithTable(): void
    {
        $this->table = $this->table($this->makeTable());

        $this->cacheSchema('tableFiltersForm', $this->getTableFiltersForm(...));

        $this->initTableColumnManager();

        if ($this->getTable()->isPaginated()) {
            $this->tableRecordsPerPage ??= $this->getDefaultTableRecordsPerPageSelectOption();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Campaign::query()->where('kategori_promosi', 'Offline'))
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
                ->withSum('dailyLeadOfflines', 'lead_didapat')
                ->withSum('dailyLeadOfflines', 'kunjungan_lokasi')
                ->withSum('dailyLeadOfflines', 'closing_utj')
            )
            ->columns([
                TextColumn::make('campaign_id')
                    ->label('ID Kampanye')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('proyek.nama_proyek')
                    ->label('Proyek')
                    ->sortable(),
                TextColumn::make('sumber_promosi')
                    ->label('Sumber')
                    ->sortable(),
                TextColumn::make('budget')
                    ->label('Budget')
                    ->money('IDR', decimalPlaces: 0, locale: 'id')
                    ->sortable(),
                TextColumn::make('daily_lead_offlines_sum_lead_didapat')
                    ->label('Lead')
                    ->sortable(),
                TextColumn::make('daily_lead_offlines_sum_kunjungan_lokasi')
                    ->label('Kunjungan')
                    ->sortable(),
                TextColumn::make('daily_lead_offlines_sum_closing_utj')
                    ->label('UTJ')
                    ->sortable(),
                TextColumn::make('cpl')
                    ->label('CPL')
                    ->getStateUsing(function (Campaign $record): float {
                        $lead = (int) $record->daily_lead_offlines_sum_lead_didapat;
                        return $lead > 0 ? (float) ($record->budget / $lead) : 0;
                    })
                    ->money('IDR', decimalPlaces: 0, locale: 'id'),
                TextColumn::make('cpa_offline')
                    ->label('CPA')
                    ->getStateUsing(function (Campaign $record): float {
                        $utj = (int) $record->daily_lead_offlines_sum_closing_utj;
                        return $utj > 0 ? (float) ($record->budget / $utj) : 0;
                    })
                    ->money('IDR', decimalPlaces: 0, locale: 'id'),
            ])
            ->defaultSort('campaign_id')
            ->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.marketing-offline-table');
    }
}
