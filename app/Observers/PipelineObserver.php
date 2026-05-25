<?php

namespace App\Observers;

use App\Services\LeadTimeService;
use Illuminate\Database\Eloquent\Model;

class PipelineObserver
{
    protected LeadTimeService $leadTimeService;

    public function __construct(LeadTimeService $leadTimeService)
    {
        $this->leadTimeService = $leadTimeService;
    }

    public function creating(Model $model): void
    {
        $this->leadTimeService->calculate($model);
    }
}
