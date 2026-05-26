<?php

namespace App\Filament\Resources\DanaTalangans\Pages;

use App\Filament\Resources\DanaTalangans\DanaTalanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDanaTalangans extends ListRecords
{
    protected static string $resource = DanaTalanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
