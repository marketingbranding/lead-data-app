<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\MarketingLeads\DailyLeadOfflineResource;
use Filament\Resources\Pages\ListRecords;

class ListDailyLeadOfflines extends ListRecords
{
    use HasExportImport;

    protected static string $resource = DailyLeadOfflineResource::class;

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
