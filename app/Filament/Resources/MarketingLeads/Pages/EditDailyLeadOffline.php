<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Resources\MarketingLeads\DailyLeadOfflineResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyLeadOffline extends EditRecord
{
    protected static string $resource = DailyLeadOfflineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
