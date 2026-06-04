<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

trait HasCreateTutupAction
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('tutup')
                ->label('Tutup')
                ->icon('heroicon-o-x-mark')
                ->color('gray')
                ->url(fn (): string => static::getResource()::getUrl('index')),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            ...($this->canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
        ];
    }
}
