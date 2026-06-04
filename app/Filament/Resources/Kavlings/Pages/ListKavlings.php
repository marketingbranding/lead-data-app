<?php

namespace App\Filament\Resources\Kavlings\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Kavlings\KavlingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKavlings extends ListRecords
{
    use HasExportImport;

    protected static string $resource = KavlingResource::class;

    protected function getExportRelations(): array
    {
        return [
            'proyek_id' => [
                'table' => 'proyeks',
                'display' => 'nama_proyek',
                'reference' => 'id',
                'label' => 'Proyek',
            ],
            'cabang_id' => [
                'table' => 'cabangs',
                'display' => 'nama',
                'reference' => 'id',
                'label' => 'Cabang',
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
