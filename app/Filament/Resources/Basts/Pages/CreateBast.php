<?php

namespace App\Filament\Resources\Basts\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Basts\BastResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBast extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = BastResource::class;
}
