<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Resources\MarketingLeads\DailyLeadOfflineResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDailyLeadOffline extends CreateRecord
{
    protected static string $resource = DailyLeadOfflineResource::class;
}
