<?php

namespace App\Filament\Resources\Basts\Pages;

use App\Filament\Resources\Basts\BastResource;
use App\Services\MundurService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBast extends EditRecord
{
    protected static string $resource = BastResource::class;

    protected function getHeaderActions(): array
    {
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
                    $this->redirect(BastResource::getUrl('index'));
                }),
        ];
    }
}
