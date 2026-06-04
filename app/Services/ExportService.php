<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    protected array $excludeColumns = ['created_at', 'updated_at', 'deleted_at'];

    public function downloadXlsx(string $table, array $relations = []): BinaryFileResponse
    {
        $columns = $this->getHeaders($table);

        $query = DB::table($table);

        $selects = [];
        foreach ($columns as $col) {
            if (isset($relations[$col])) {
                $rel = $relations[$col];
                $selects[] = DB::raw("{$rel['table']}.{$rel['display']} as {$col}");
                $query->leftJoin($rel['table'], "{$table}.{$col}", '=', "{$rel['table']}.{$rel['reference']}");
            } else {
                $selects[] = "{$table}.{$col}";
            }
        }

        $rows = $query->select($selects)->get();

        $headers = [];
        foreach ($columns as $col) {
            if (isset($relations[$col])) {
                $headers[] = $relations[$col]['label'] ?? $col;
            } else {
                $headers[] = $col;
            }
        }

        $filename = $table . '_' . date('Y-m-d') . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . uniqid('export_', true) . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($tempPath);

        $headerRow = Row::fromValues($headers, (new Style())->setFontBold());
        $writer->addRow($headerRow);

        foreach ($rows as $row) {
            $data = (array) $row;
            $values = [];
            foreach ($columns as $col) {
                $values[] = $data[$col] ?? '';
            }
            $writer->addRow(Row::fromValues($values));
        }

        $writer->close();

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    protected function getHeaders(string $table): array
    {
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        return array_values(array_filter($columns, fn ($c) => !in_array($c, $this->excludeColumns)));
    }
}
