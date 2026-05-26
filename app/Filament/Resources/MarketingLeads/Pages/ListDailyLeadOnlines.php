<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\MarketingLeads\DailyLeadOnlineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyLeadOnlines extends ListRecords
{
    use HasExportImport;

    protected static string $resource = DailyLeadOnlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
