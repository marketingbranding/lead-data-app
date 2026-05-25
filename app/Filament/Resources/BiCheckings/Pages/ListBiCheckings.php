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

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
