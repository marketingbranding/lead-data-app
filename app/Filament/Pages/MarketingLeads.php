<?php

namespace App\Filament\Pages;

use App\Filament\Pages\MarketingDashboard;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class MarketingLeads extends Page
{
    protected static ?string $navigationLabel = 'Marketing Leads';

    protected string $view = 'filament.pages.group-page';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?int $navigationSort = 9;

    public function mount(): void
    {
        $this->redirect(MarketingDashboard::getUrl());
    }
}
