<?php

namespace App\Filament\Resources\Proyeks\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Proyeks\ProyekResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProyeks extends ListRecords
{
    use HasExportImport;

    protected static string $resource = ProyekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
