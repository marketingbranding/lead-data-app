<?php

namespace App\Filament\Resources\PpjbDevs\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\PpjbDevs\PpjbDevResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePpjbDev extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = PpjbDevResource::class;
}
