<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;

class ImportService
{
    protected array $excludeColumns = ['created_at', 'updated_at'];

    public function importXlsx(string $table, string $filePath, array $relations = []): array
    {
        $headers = $this->getHeaders($table);
        $pk = $this->getPrimaryKey($table);
        $reader = new Reader();
        $reader->open($filePath);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            $isFirstRow = true;
            foreach ($sheet->getRowIterator() as $row) {
                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue;
                }

                $cells = $row->toArray();
                $data = [];
                foreach ($headers as $i => $col) {
                    $data[$col] = $cells[$i] ?? null;
                }

                $data = array_filter($data, fn ($v) => $v !== null && $v !== '', ARRAY_FILTER_USE_BOTH);

                try {
                    DB::beginTransaction();

                    foreach ($relations as $fkCol => $rel) {
                        if (isset($data[$fkCol]) && $data[$fkCol] !== '') {
                            $lookup = DB::table($rel['table'])
                                ->where($rel['display'], $data[$fkCol])
                                ->first();

                            if (!$lookup) {
                                throw new \Exception(
                                    ($rel['label'] ?? $fkCol) . " '{$data[$fkCol]}' tidak ditemukan"
                                );
                            }

                            $data[$fkCol] = $lookup->{$rel['reference']};
                        }
                    }

                    if ($pk && isset($data[$pk])) {
                        DB::table($table)->updateOrInsert([$pk => $data[$pk]], $data);
                    } else {
                        DB::table($table)->insert($data);
                    }

                    DB::commit();
                    $imported++;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $errors[] = 'Baris ' . ($imported + $skipped + 2) . ': ' . $e->getMessage();
                    $skipped++;
                }
            }
        }

        $reader->close();

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    protected function getHeaders(string $table): array
    {
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        return array_values(array_filter($columns, fn ($c) => !in_array($c, $this->excludeColumns)));
    }

    protected function getPrimaryKey(string $table): ?string
    {
        $keys = DB::select("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'");
        return $keys[0]->Column_name ?? null;
    }
}
