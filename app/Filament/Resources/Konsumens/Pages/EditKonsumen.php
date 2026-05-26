<?php

namespace App\Filament\Resources\Konsumens\Pages;

use App\Filament\Resources\Konsumens\KonsumenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKonsumen extends EditRecord
{
    protected static string $resource = KonsumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
