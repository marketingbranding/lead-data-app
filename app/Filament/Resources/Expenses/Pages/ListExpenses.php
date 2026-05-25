<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpenses extends ListRecords
{
    use HasExportImport;

    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
