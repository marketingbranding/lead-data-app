<?php

namespace App\Filament\Pages;

use App\Models\Campaign;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use UnitEnum;

class MarketingDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $title = 'Marketing Dashboard';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Marketing Leads';

    protected static ?int $navigationSort = 9;

    protected string $view = 'filament.pages.marketing-dashboard';

    public string $tab = 'offline';

    public function getOfflineData(): Collection
    {
        return Campaign::query()
            ->where('kategori_promosi', 'Offline')
            ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
            ->get()
            ->map(function ($c) {
                $totalLead = $c->dailyLeadOfflines()->sum('lead_didapat');
                $totalUtj = $c->dailyLeadOfflines()->sum('closing_utj');
                return (object) [
                    'campaign_id' => $c->campaign_id,
                    'proyek' => $c->proyek?->nama_proyek,
                    'sumber_promosi' => $c->sumber_promosi,
                    'budget' => (int) $c->budget,
                    'total_lead' => $totalLead,
                    'total_kunjungan' => $c->dailyLeadOfflines()->sum('kunjungan_lokasi'),
                    'total_utj' => $totalUtj,
                    'cpl' => $totalLead > 0 ? (int) ($c->budget / $totalLead) : 0,
                    'cpa' => $totalUtj > 0 ? (int) ($c->budget / $totalUtj) : 0,
                ];
            });
    }

    public function getOnlineData(): Collection
    {
        return Campaign::query()
            ->where('kategori_promosi', 'Online')
            ->when(auth()->user()?->cabang_id, fn ($q, $v) => $q->where('cabang_id', $v))
            ->get()
            ->map(function ($c) {
                $totalUtj = $c->dailyLeadOnlines()->sum('closing_utj');
                return (object) [
                    'campaign_id' => $c->campaign_id,
                    'proyek' => $c->proyek?->nama_proyek,
                    'sumber_promosi' => $c->sumber_promosi,
                    'budget' => (int) $c->budget,
                    'total_klik' => $c->dailyLeadOnlines()->sum('klik_tautan'),
                    'total_lead_masuk' => $c->dailyLeadOnlines()->sum('lead_masuk'),
                    'total_respon' => $c->dailyLeadOnlines()->sum('respon'),
                    'total_diskusi' => $c->dailyLeadOnlines()->sum('tahap_diskusi'),
                    'total_cek_lokasi' => $c->dailyLeadOnlines()->sum('cek_lokasi'),
                    'total_utj' => $totalUtj,
                    'cpa' => $totalUtj > 0 ? (int) ($c->budget / $totalUtj) : 0,
                ];
            });
    }
}
