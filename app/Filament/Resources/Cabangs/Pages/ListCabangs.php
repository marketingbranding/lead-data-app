<?php

namespace App\Filament\Resources\Cabangs\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Cabangs\CabangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCabangs extends ListRecords
{
    use HasExportImport;

    protected static string $resource = CabangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
