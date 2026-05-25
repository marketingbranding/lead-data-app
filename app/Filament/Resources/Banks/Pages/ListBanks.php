<?php

namespace App\Filament\Resources\Banks\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Banks\BankResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBanks extends ListRecords
{
    use HasExportImport;

    protected static string $resource = BankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
