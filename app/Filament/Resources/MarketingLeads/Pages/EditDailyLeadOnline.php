<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Resources\MarketingLeads\DailyLeadOnlineResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyLeadOnline extends EditRecord
{
    protected static string $resource = DailyLeadOnlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
