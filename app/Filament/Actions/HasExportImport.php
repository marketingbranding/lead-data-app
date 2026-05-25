<?php

namespace App\Filament\Actions;

use App\Services\ExportService;
use App\Services\ImportService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;

trait HasExportImport
{
    protected function getExportImportActions(): array
    {
        return [
            $this->getExportAction(),
            $this->getImportAction(),
        ];
    }

    protected function getExportAction(): Action
    {
        $table = app(static::$resource::getModel())->getTable();

        return Action::make('exportExcel')
            ->label('Export Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(function () use ($table) {
                $service = app(ExportService::class);
                return $service->downloadXlsx($table);
            });
    }

    protected function getImportAction(): Action
    {
        $table = app(static::$resource::getModel())->getTable();

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
            ->action(function (array $data) use ($table) {
                $file = $data['file'];

                if (!is_string($file)) {
                    $tmpPath = $file->getRealPath();
                } else {
                    $tmpPath = storage_path('app/' . $file);
                }

                $service = app(ImportService::class);
                $result = $service->importXlsx($table, $tmpPath);

                Notification::make()
                    ->title('Import Selesai')
                    ->body("Berhasil: {$result['imported']}, Dilewati: {$result['skipped']}")
                    ->success()
                    ->send();

                if (!empty($result['errors'])) {
                    foreach (array_slice($result['errors'], 0, 5) as $error) {
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
