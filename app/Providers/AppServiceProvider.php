<?php

namespace App\Providers;

use App\Models\Akad;
use App\Models\Bast;
use App\Models\BiChecking;
use App\Models\Campaign;
use App\Models\DailyLeadOffline;
use App\Models\DailyLeadOnline;
use App\Models\Expense;
use App\Models\Kavling;
use App\Models\Konsumen;
use App\Models\Pemberkasan;
use App\Models\PipelineLog;
use App\Models\PpjbDev;
use App\Models\ProsesBank;
use App\Models\Psjb;
use App\Observers\PipelineLogObserver;
use App\Observers\PipelineObserver;
use App\Observers\RejectKavlingObserver;
use App\Observers\StatusDataObserver;
use App\Scopes\CabangScope;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $pipelineModels = [
            Psjb::class,
            Pemberkasan::class,
            ProsesBank::class,
            PpjbDev::class,
            Akad::class,
            Bast::class,
        ];

        foreach ($pipelineModels as $model) {
            $model::observe(PipelineObserver::class);
            $model::observe(PipelineLogObserver::class);
        }
        BiChecking::observe(PipelineLogObserver::class);
        ProsesBank::observe(RejectKavlingObserver::class);

        $statusDataModels = [
            Konsumen::class,
            BiChecking::class,
            Psjb::class,
            Pemberkasan::class,
            ProsesBank::class,
            PpjbDev::class,
            Akad::class,
            Bast::class,
        ];

        foreach ($statusDataModels as $model) {
            $model::observe(StatusDataObserver::class);
        }

        $cabangScopedModels = [
            Kavling::class,
            Konsumen::class,
            BiChecking::class,
            Psjb::class,
            Pemberkasan::class,
            ProsesBank::class,
            PpjbDev::class,
            Akad::class,
            Bast::class,
            Expense::class,
            PipelineLog::class,
            Campaign::class,
            DailyLeadOffline::class,
            DailyLeadOnline::class,
        ];

        foreach ($cabangScopedModels as $model) {
            $model::addGlobalScope(new CabangScope);
        }
    }
}
