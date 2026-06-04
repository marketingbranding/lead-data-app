<?php

namespace App\Filament\Resources\Psjbs\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Psjbs\PsjbResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePsjb extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = PsjbResource::class;
}
