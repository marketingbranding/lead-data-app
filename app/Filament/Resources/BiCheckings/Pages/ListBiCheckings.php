<?php

namespace App\Filament\Resources\BiCheckings\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\BiCheckings\BiCheckingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBiCheckings extends ListRecords
{
    use HasExportImport;

    protected static string $resource = BiCheckingResource::class;

    protected function getExportRelations(): array
    {
        return [
            'id_kavling' => [
                'table' => 'kavlings',
                'display' => 'kode_kavling',
                'reference' => 'id_kavling',
                'label' => 'Kavling',
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
