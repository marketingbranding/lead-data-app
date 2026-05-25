<?php

namespace App\Filament\Resources\Kavlings\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\Kavlings\KavlingResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Writer;
use Illuminate\Support\Facades\DB;

class ListKavlings extends ListRecords
{
    use HasExportImport;

    protected static string $resource = KavlingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getCustomExportAction(),
            $this->getCustomImportAction(),
            CreateAction::make(),
        ];
    }

    protected function getCustomExportAction(): Action
    {
        return Action::make('exportExcel')
            ->label('Export Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function () {
                $rows = DB::table('kavlings')
                    ->leftJoin('proyeks', 'kavlings.proyek_id', '=', 'proyeks.id')
                    ->leftJoin('cabangs', 'kavlings.cabang_id', '=', 'cabangs.id')
                    ->select(
                        'kavlings.id_kavling',
                        'proyeks.nama_proyek as proyek',
                        'cabangs.nama as cabang',
                        'kavlings.kode_kavling',
                        'kavlings.luas_bangunan_m2',
                        'kavlings.luas_tanah_m2',
                        'kavlings.progres_bangun',
                        'kavlings.harga',
                        'kavlings.status_kavling',
                    )
                    ->orderBy('kavlings.id_kavling')
                    ->get();

                $headers = ['id_kavling', 'proyek', 'cabang', 'kode_kavling', 'luas_bangunan_m2', 'luas_tanah_m2', 'progres_bangun', 'harga', 'status_kavling'];

                $filename = 'kavlings_' . date('Y-m-d') . '.xlsx';
                $tempPath = sys_get_temp_dir() . '/' . uniqid('export_', true) . '.xlsx';

                $writer = new Writer();
                $writer->openToFile($tempPath);
                $writer->addRow(Row::fromValues($headers, (new Style())->setFontBold()));

                foreach ($rows as $row) {
                    $values = [];
                    foreach ($headers as $col) {
                        $values[] = $row->$col ?? '';
                    }
                    $writer->addRow(Row::fromValues($values));
                }

                $writer->close();

                return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
            });
    }

    protected function getCustomImportAction(): Action
    {
        return Action::make('importExcel')
            ->label('Import Excel')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('warning')
            ->form([
                FileUpload::make('file')
                    ->label('File Excel (.xlsx)')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ->required()
                    ->storeFiles(false),
            ])
            ->action(function (array $data) {
                $file = $data['file'];
                $tmpPath = is_string($file) ? storage_path('app/' . $file) : $file->getRealPath();

                $reader = new Reader();
                $reader->open($tmpPath);

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
                        $idKavling = $cells[0] ?? null;
                        $namaProyek = trim($cells[1] ?? '');
                        $namaCabang = trim($cells[2] ?? '');
                        $kodeKavling = $cells[3] ?? null;
                        $luasBangunan = $cells[4] ?? null;
                        $luasTanah = $cells[5] ?? null;
                        $progresBangun = $cells[6] ?? null;
                        $harga = $cells[7] ?? null;
                        $statusKavling = $cells[8] ?? 'Tersedia';

                        if (!$idKavling) {
                            $skipped++;
                            continue;
                        }

                        try {
                            DB::beginTransaction();

                            // Find or create proyek
                            $cabang = DB::table('cabangs')->where('nama', $namaCabang)->first();
                            if (!$cabang) {
                                throw new \Exception("Cabang '$namaCabang' tidak ditemukan");
                            }

                            $proyek = DB::table('proyeks')
                                ->where('nama_proyek', $namaProyek)
                                ->where('cabang_id', $cabang->id)
                                ->first();

                            if (!$proyek) {
                                $proyekId = DB::table('proyeks')->insertGetId([
                                    'nama_proyek' => $namaProyek,
                                    'cabang_id' => $cabang->id,
                                ]);
                            } else {
                                $proyekId = $proyek->id;
                            }

                            // Insert kavling
                            $insertData = [
                                'id_kavling' => $idKavling,
                                'proyek_id' => $proyekId,
                                'cabang_id' => $cabang->id,
                                'kode_kavling' => $kodeKavling,
                                'status_kavling' => $statusKavling ?: 'Tersedia',
                            ];

                            if ($luasBangunan !== null && $luasBangunan !== '') $insertData['luas_bangunan_m2'] = $luasBangunan;
                            if ($luasTanah !== null && $luasTanah !== '') $insertData['luas_tanah_m2'] = $luasTanah;
                            if ($progresBangun !== null && $progresBangun !== '') $insertData['progres_bangun'] = $progresBangun;
                            if ($harga !== null && $harga !== '') $insertData['harga'] = $harga;

                            DB::table('kavlings')->updateOrInsert(
                                ['id_kavling' => $idKavling],
                                $insertData
                            );

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

                Notification::make()
                    ->title('Import Selesai')
                    ->body("Berhasil: {$imported}, Dilewati: {$skipped}")
                    ->success()
                    ->send();

                if (!empty($errors)) {
                    foreach (array_slice($errors, 0, 5) as $error) {
                        Notification::make()
                            ->title('Error')
                            ->body($error)
                            ->danger()
                            ->send();
                    }
                }

                $this->redirect(request()->url());
            });
    }
}
