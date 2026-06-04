<?php

namespace App\Filament\Resources\Psjbs\Pages;

use App\Filament\Resources\Psjbs\PsjbResource;
use App\Services\MundurService;
use App\Services\PipelineFlowService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPsjb extends EditRecord
{
    protected static string $resource = PsjbResource::class;

    protected function getHeaderActions(): array
    {
        $service = app(PipelineFlowService::class);

        return [
            DeleteAction::make(),
            Action::make('mundur')
                ->label('Mundur')
                ->icon('heroicon-o-arrow-left-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Mundurkan Proses')
                ->modalDescription('Apakah Anda yakin ingin memundurkan proses ini? Konsumen akan ditandai sebagai mundur.')
                ->visible(fn (): bool => $this->record->kavling?->konsumens()->where('status_konsumen', 'aktif')->exists())
                ->action(function () {
                    app(MundurService::class)->mundurkan($this->record);
                    $this->redirect(PsjbResource::getUrl('index'));
                }),
            Action::make('lanjutTahap')
                ->label($service->getNextStageLabel($this->record))
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status_data === 'Data Lengkap')
                ->action(fn () => redirect($service->getNextStageEditUrl($this->record))),
        ];
    }
}
