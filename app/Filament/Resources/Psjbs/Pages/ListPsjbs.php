<?php

namespace App\Filament\Resources\Psjbs\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Psjbs\PsjbResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPsjbs extends ListRecords
{
    use HasExportImport;

    protected static string $resource = PsjbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
