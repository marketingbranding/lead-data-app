<?php

namespace App\Filament\Resources\Basts\Pages;

use App\Filament\Resources\Basts\BastResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBast extends EditRecord
{
    protected static string $resource = BastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
