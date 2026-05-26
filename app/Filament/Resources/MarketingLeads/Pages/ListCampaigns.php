<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\MarketingLeads\CampaignResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCampaigns extends ListRecords
{
    use HasExportImport;

    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
