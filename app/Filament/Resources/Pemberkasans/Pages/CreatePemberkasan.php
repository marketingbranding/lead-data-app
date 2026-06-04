<?php

namespace App\Filament\Resources\Pemberkasans\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Pemberkasans\PemberkasanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePemberkasan extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = PemberkasanResource::class;
}
