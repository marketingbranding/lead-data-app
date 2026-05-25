<?php

namespace App\Filament\Resources\Konsumens\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Konsumens\KonsumenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKonsumens extends ListRecords
{
    use HasExportImport;

    protected static string $resource = KonsumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
