<?php

namespace App\Filament\Resources\Banks\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Banks\BankResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBank extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = BankResource::class;
}
