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
            Action::make('resubmit')
                ->label('Resubmit')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (): bool =>
                    $this->record->jenis_respon === 'Revisi' &&
                    $this->record->revisiProsesBanks()->count() > 0 &&
                    $this->record->revisiProsesBanks()->where('status', '!=', 'selesai')->count() === 0
                )
                ->action(function () {
                    $this->record->jenis_respon = 'Approved';
                    $this->record->save();
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record]));
                }),
            Action::make('lanjutTahap')
                ->label($service->getNextStageLabel($this->record))
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status_data === 'Data Lengkap' && !in_array($this->record->jenis_respon, ['Reject', 'Revisi']))
                ->action(fn () => redirect($service->getNextStageEditUrl($this->record))),
        ];
    }
}
