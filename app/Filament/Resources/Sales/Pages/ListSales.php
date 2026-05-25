<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Sales\SalesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSales extends ListRecords
{
    use HasExportImport;

    protected static string $resource = SalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
