<?php

namespace App\Filament\Resources\PipelineLogs\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\PipelineLogs\PipelineLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPipelineLogs extends ListRecords
{
    use HasExportImport;

    protected static string $resource = PipelineLogResource::class;

    protected function getExportRelations(): array
    {
        return [
            'id_kavling' => [
                'table' => 'kavlings',
                'display' => 'kode_kavling',
                'reference' => 'id_kavling',
                'label' => 'Kavling',
            ],
            'id_konsumen' => [
                'table' => 'konsumens',
                'display' => 'nama_konsumen',
                'reference' => 'id_konsumen',
                'label' => 'Konsumen',
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
