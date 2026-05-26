<?php

namespace App\Services;

use App\Models\Campaign;

class CampaignIdService
{
    public function generate(): string
    {
        $last = Campaign::where('campaign_id', 'like', 'CMP-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(campaign_id, "-", -1) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->value('campaign_id');

        $nextNumber = $last ? (int) substr($last, -4) + 1 : 1;

        return 'CMP-' . now()->format('Ym') . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
