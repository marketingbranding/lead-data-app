<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\MarketingLeads\DailyLeadOnlineResource;
use Filament\Resources\Pages\ListRecords;

class ListDailyLeadOnlines extends ListRecords
{
    use HasExportImport;

    protected static string $resource = DailyLeadOnlineResource::class;

    protected function getExportRelations(): array
    {
        return [
            'campaign_id' => [
                'table' => 'campaigns',
                'display' => 'sumber_promosi',
                'reference' => 'id',
                'label' => 'Campaign',
            ],
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
        ];
    }
}
