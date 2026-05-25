<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    use HasExportImport;

    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
