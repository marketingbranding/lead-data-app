<?php

namespace App\Filament\Resources\Akads\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Akads\AkadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAkads extends ListRecords
{
    use HasExportImport;

    protected static string $resource = AkadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
