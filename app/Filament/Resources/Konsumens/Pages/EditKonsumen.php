<?php

namespace App\Filament\Resources\Konsumens\Pages;

use App\Filament\Resources\Konsumens\KonsumenResource;
use App\Services\PipelineFlowService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKonsumen extends EditRecord
{
    protected static string $resource = KonsumenResource::class;

    protected function getHeaderActions(): array
    {
        $service = app(PipelineFlowService::class);

        return [
            DeleteAction::make(),
            Action::make('lanjutTahap')
                ->label($service->getNextStageLabel($this->record))
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->status_data === 'Data Lengkap')
                ->action(fn () => redirect($service->getNextStageEditUrl($this->record))),
        ];
    }
}
