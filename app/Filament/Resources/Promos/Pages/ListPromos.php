<?php

namespace App\Filament\Resources\Promos\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Promos\PromoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromos extends ListRecords
{
    use HasExportImport;

    protected static string $resource = PromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
            CreateAction::make(),
        ];
    }
}
