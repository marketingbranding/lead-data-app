<?php

namespace App\Filament\Resources\Pemberkasans\Pages;

use App\Filament\Resources\Pemberkasans\PemberkasanResource;
use App\Services\PipelineFlowService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPemberkasan extends EditRecord
{
    protected static string $resource = PemberkasanResource::class;

    protected function getHeaderActions(): array
    {
        $service = app(PipelineFlowService::class);

        return [
            DeleteAction::make(),
            Action::make('resubmit')
                ->label('Resubmit')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (): bool =>
                    $this->record->revisiPemberkasans()->count() > 0 &&
                    $this->record->revisiPemberkasans()->where('status', '!=', 'selesai')->count() === 0
                )
                ->action(function () {
                    $this->record->tipe_pemberkasan = 'lengkap';
                    $this->record->save();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),
            Action::make('lanjutTahap')
                ->label($service->getNextStageLabel($this->record))
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status_data === 'Data Lengkap' && !$this->record->revisiPemberkasans()->where('status', 'pending')->exists())
                ->action(fn () => redirect($service->getNextStageEditUrl($this->record))),
        ];
    }
}
