<?php

namespace App\Filament\Pages;

use App\Models\Campaign;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MarketingDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $title = 'Marketing Dashboard';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Marketing Leads';

    protected static ?int $navigationSort = 9;

    protected string $view = 'filament.pages.marketing-dashboard';

    public string $tab = 'offline';

    public function table(Table $table): Table
    {
        return $table
            ->query(Campaign::query())
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->where('kategori_promosi', $this->tab === 'offline' ? 'Offline' : 'Online')
                ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
                ->when($this->tab === 'offline', fn ($q) => $q
                    ->withSum('dailyLeadOfflines', 'lead_didapat')
                    ->withSum('dailyLeadOfflines', 'kunjungan_lokasi')
                    ->withSum('dailyLeadOfflines', 'closing_utj'))
                ->when($this->tab === 'online', fn ($q) => $q
                    ->withSum('dailyLeadOnlines', 'klik_tautan')
                    ->withSum('dailyLeadOnlines', 'lead_masuk')
                    ->withSum('dailyLeadOnlines', 'respon')
                    ->withSum('dailyLeadOnlines', 'tahap_diskusi')
                    ->withSum('dailyLeadOnlines', 'cek_lokasi')
                    ->withSum('dailyLeadOnlines', 'closing_utj'))
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
                // Offline columns
                TextColumn::make('daily_lead_offlines_sum_lead_didapat')
                    ->label('Lead')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'offline'),
                TextColumn::make('daily_lead_offlines_sum_kunjungan_lokasi')
                    ->label('Kunjungan')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'offline'),
                TextColumn::make('daily_lead_offlines_sum_closing_utj')
                    ->label('UTJ')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'offline'),
                TextColumn::make('cpl')
                    ->label('CPL')
                    ->getStateUsing(function (Campaign $record): float {
                        $lead = (int) $record->daily_lead_offlines_sum_lead_didapat;
                        return $lead > 0 ? (float) ($record->budget / $lead) : 0;
                    })
                    ->money('IDR', decimalPlaces: 0, locale: 'id')
                    ->hidden(fn () => $this->tab !== 'offline'),
                TextColumn::make('cpa_offline')
                    ->label('CPA')
                    ->getStateUsing(function (Campaign $record): float {
                        $utj = (int) $record->daily_lead_offlines_sum_closing_utj;
                        return $utj > 0 ? (float) ($record->budget / $utj) : 0;
                    })
                    ->money('IDR', decimalPlaces: 0, locale: 'id')
                    ->hidden(fn () => $this->tab !== 'offline'),
                // Online columns
                TextColumn::make('daily_lead_onlines_sum_klik_tautan')
                    ->label('Klik')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'online'),
                TextColumn::make('daily_lead_onlines_sum_lead_masuk')
                    ->label('Lead')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'online'),
                TextColumn::make('daily_lead_onlines_sum_respon')
                    ->label('Respon')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'online'),
                TextColumn::make('daily_lead_onlines_sum_tahap_diskusi')
                    ->label('Diskusi')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'online'),
                TextColumn::make('daily_lead_onlines_sum_cek_lokasi')
                    ->label('Cek Lokasi')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'online'),
                TextColumn::make('daily_lead_onlines_sum_closing_utj')
                    ->label('UTJ')
                    ->sortable()
                    ->hidden(fn () => $this->tab !== 'online'),
                TextColumn::make('cpa_online')
                    ->label('CPA')
                    ->getStateUsing(function (Campaign $record): float {
                        $utj = (int) $record->daily_lead_onlines_sum_closing_utj;
                        return $utj > 0 ? (float) ($record->budget / $utj) : 0;
                    })
                    ->money('IDR', decimalPlaces: 0, locale: 'id')
                    ->hidden(fn () => $this->tab !== 'online'),
            ])
            ->defaultSort('campaign_id')
            ->paginated(false);
    }
}
