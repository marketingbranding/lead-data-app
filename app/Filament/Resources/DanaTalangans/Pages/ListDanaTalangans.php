<?php

namespace App\Filament\Resources\DanaTalangans\Pages;

use App\Filament\Resources\DanaTalangans\DanaTalanganResource;
use App\Models\DanaTalangan;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDanaTalangans extends ListRecords
{
    protected static string $resource = DanaTalanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('bbgReminder')
                ->label('')
                ->icon('heroicon-o-bell-alert')
                ->badge(fn () => $this->getBbgReminderQuery()->count())
                ->badgeColor('warning')
                ->color('warning')
                ->modalHeading('BBG Reminder')
                ->modalContent(fn () => view('filament.modals.bbg-reminder', [
                    'records' => $this->getBbgReminderQuery()->get(),
                ]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
            CreateAction::make(),
        ];
    }

    protected function getBbgReminderQuery()
    {
        $query = DanaTalangan::query()
            ->whereHas('cabang')
            ->whereHas('konsumen')
            ->whereDate('tgl_bbg_due', '>', now())
            ->whereDate('tgl_bbg_due', '<=', now()->addDays(30));

        if (auth()->user()?->hasRole('admin-cabang')) {
            $query->where('cabang_id', auth()->user()->cabang_id);
        }

        return $query;
    }
}
