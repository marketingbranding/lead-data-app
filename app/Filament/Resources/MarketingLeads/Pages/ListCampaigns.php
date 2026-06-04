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

    protected function getExportRelations(): array
    {
        return [
            'cabang_id' => [
                'table' => 'cabangs',
                'display' => 'nama',
                'reference' => 'id',
                'label' => 'Cabang',
            ],
            'proyek_id' => [
                'table' => 'proyeks',
                'display' => 'nama_proyek',
                'reference' => 'id',
                'label' => 'Proyek',
            ],
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
