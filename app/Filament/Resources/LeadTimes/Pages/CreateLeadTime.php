<?php

namespace App\Filament\Resources\LeadTimes\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\LeadTimes\LeadTimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadTime extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = LeadTimeResource::class;
}
