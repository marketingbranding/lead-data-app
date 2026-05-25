<?php

namespace App\Filament\Resources\Basts\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Basts\BastResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBasts extends ListRecords
{
    use HasExportImport;

    protected static string $resource = BastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
