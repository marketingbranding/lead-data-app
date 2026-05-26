<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CampaignAutoStatus extends Command
{
    protected $signature = 'campaigns:update-status';
    protected $description = 'Ubah status campaign yang sudah lewat tanggal selesai menjadi Selesai';

    public function handle(): void
    {
        $updated = Campaign::where('status', 'Berlangsung')
            ->where('tanggal_selesai', '<', Carbon::today())
            ->update(['status' => 'Selesai']);

        $this->info("{$updated} campaign diubah ke status Selesai.");
    }
}
