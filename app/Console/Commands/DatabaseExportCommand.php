<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseExportCommand extends Command
{
    protected $signature = 'db:export';
    protected $description = 'Export all data tables to JSON files in database/sync/';

    private array $excludeTables = [
        'migrations', 'cache', 'cache_locks', 'sessions',
        'jobs', 'job_batches', 'failed_jobs',
        'password_reset_tokens', 'personal_access_tokens',
    ];

    public function handle(): void
    {
        $syncPath = database_path('sync');
        $tables = DB::select('SHOW TABLES');
        $key = 'Tables_in_' . DB::getDatabaseName();

        foreach ($tables as $table) {
            $name = $table->$key;
            if (in_array($name, $this->excludeTables)) {
                continue;
            }

            $pk = DB::getSchemaBuilder()->getColumnListing($name)[0] ?? 'id';
            $rows = DB::table($name)->orderBy($pk)->get()->toArray();
            $data = array_map(fn ($row) => (array) $row, $rows);

            $filePath = $syncPath . '/' . $name . '.json';
            file_put_contents(
                $filePath,
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );

            $this->info("Exported: {$name} (" . count($data) . " rows)");
        }

        $this->newLine();
        $this->info('Export complete! Files are in database/sync/.');
        $this->warn('Commit them with: git add database/sync/ && git commit -m "sync: update database data"');
    }
}
