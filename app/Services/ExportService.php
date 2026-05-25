<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    protected array $excludeColumns = ['created_at', 'updated_at'];

    public function downloadXlsx(string $table): BinaryFileResponse
    {
        $headers = $this->getHeaders($table);
        $rows = DB::table($table)->get();

        $filename = $table . '_' . date('Y-m-d') . '.xlsx';
        $tempPath = sys_get_temp_dir() . '/' . uniqid('export_', true) . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($tempPath);

        $headerRow = Row::fromValues($headers, (new Style())->setFontBold());
        $writer->addRow($headerRow);

        foreach ($rows as $row) {
            $data = (array) $row;
            $values = [];
            foreach ($headers as $col) {
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
