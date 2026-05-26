<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Resources\MarketingLeads\DailyLeadOnlineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyLeadOnlines extends ListRecords
{
    protected static string $resource = DailyLeadOnlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
