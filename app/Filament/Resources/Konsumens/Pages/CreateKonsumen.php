<?php

namespace App\Filament\Resources\Konsumens\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Konsumens\KonsumenResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKonsumen extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = KonsumenResource::class;
}
