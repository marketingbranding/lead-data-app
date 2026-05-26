<?php

namespace App\Filament\Resources\MarketingLeads\Pages;

use App\Filament\Resources\MarketingLeads\CampaignResource;
use App\Services\CampaignIdService;
use Filament\Resources\Pages\CreateRecord;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['cabang_id'] ??= auth()->user()->cabang_id;
        $data['campaign_id'] = app(CampaignIdService::class)->generate();
        return $data;
    }
}
