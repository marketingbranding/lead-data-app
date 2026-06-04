<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Sales\SalesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSale extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = SalesResource::class;
}
