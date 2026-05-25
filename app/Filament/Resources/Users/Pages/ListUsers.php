<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    use HasExportImport;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
