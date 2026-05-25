<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SalesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSale extends EditRecord
{
    protected static string $resource = SalesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
