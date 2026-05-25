<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSyncSeeder extends Seeder
{
    private array $orderedTables = [
        // parents first
        'cabangs',
        'banks',
        'proyeks',
        'permissions',
        'roles',
        'role_has_permissions',
        'model_has_roles',
        'model_has_permissions',
        'kavlings',
        'sales',
        'promos',
        'users',
        'konsumens',
        'lead_times',
        'bi_checking',
        'psjb',
        'pemberkasan',
        'proses_bank',
        'ppjb_dev',
        'akad',
        'bast',
        'expenses',
        'pipeline_logs',
    ];

    public function run(): void
    {
        $syncPath = database_path('sync');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach (array_reverse($this->orderedTables) as $table) {
            DB::table($table)->truncate();
        }

        foreach ($this->orderedTables as $table) {
            $filePath = $syncPath . '/' . $table . '.json';
            if (! file_exists($filePath)) {
                continue;
            }

            $data = json_decode(file_get_contents($filePath), true);
            if (empty($data)) {
                continue;
            }

            $chunks = array_chunk($data, 100);
            foreach ($chunks as $chunk) {
                DB::table($table)->insert($chunk);
            }

            $this->command->info("Imported: {$table} (" . count($data) . " rows)");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->newLine();
        $this->command->info('Sync data imported successfully!');
    }
}
