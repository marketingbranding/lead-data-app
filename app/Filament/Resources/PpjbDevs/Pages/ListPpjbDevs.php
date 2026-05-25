<?php

namespace App\Filament\Resources\PpjbDevs\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\PpjbDevs\PpjbDevResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPpjbDevs extends ListRecords
{
    use HasExportImport;

    protected static string $resource = PpjbDevResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
