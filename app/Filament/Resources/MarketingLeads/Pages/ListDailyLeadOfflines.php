<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\MarketingLeads\DailyLeadOfflineResource;
use Filament\Resources\Pages\ListRecords;

class ListDailyLeadOfflines extends ListRecords
{
    use HasExportImport;

    protected static string $resource = DailyLeadOfflineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
        ];
    }
}
