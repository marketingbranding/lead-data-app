<?php

namespace App\Filament\Resources\ProsesBanks\Pages;

use App\Filament\Resources\ProsesBanks\ProsesBankResource;
use App\Services\PipelineFlowService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProsesBank extends EditRecord
{
    protected static string $resource = ProsesBankResource::class;

    protected function getHeaderActions(): array
    {
        $service = app(PipelineFlowService::class);

        return [
            DeleteAction::make(),
            Action::make('lanjutTahap')
                ->label($service->getNextStageLabel($this->record))
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status_data === 'Data Lengkap' && !in_array($this->record->jenis_respon, ['Reject', 'Revisi']))
                ->action(fn () => redirect($service->getNextStageEditUrl($this->record))),
        ];
    }
}
