<?php

namespace App\Filament\Pages;

use App\Livewire\MarketingOfflineTable;
use App\Livewire\MarketingOnlineTable;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MarketingDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $title = 'Marketing Dashboard';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Marketing Leads';

    protected static ?int $navigationSort = 9;

    public string $tab = 'offline';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Laporan')
                    ->livewireProperty('tab')
                    ->contained(false)
                    ->tabs([
                        Tab::make('Laporan Offline')
                            ->key('offline')
                            ->icon('heroicon-o-clipboard-document')
                            ->schema([
                                EmbeddedTable::make(MarketingOfflineTable::class),
                            ]),
                        Tab::make('Laporan Online')
                            ->key('online')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                EmbeddedTable::make(MarketingOnlineTable::class),
                            ]),
                    ]),
            ]);
    }
}
