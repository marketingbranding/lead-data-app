<?php

namespace App\Filament\Resources\DanaTalangans\Pages;

use App\Filament\Actions\HasExportImport;
use App\Filament\Resources\DanaTalangans\DanaTalanganResource;
use App\Models\DanaTalangan;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDanaTalangans extends ListRecords
{
    use HasExportImport;

    protected static string $resource = DanaTalanganResource::class;

    protected function getExportRelations(): array
    {
        return [
            'cabang_id' => [
                'table' => 'cabangs',
                'display' => 'nama',
                'reference' => 'id',
                'label' => 'Cabang',
            ],
            'proyek_id' => [
                'table' => 'proyeks',
                'display' => 'nama_proyek',
                'reference' => 'id',
                'label' => 'Proyek',
            ],
            'kavling_id' => [
                'table' => 'kavlings',
                'display' => 'kode_kavling',
                'reference' => 'id_kavling',
                'label' => 'Kavling',
            ],
            'konsumen_id' => [
                'table' => 'konsumens',
                'display' => 'nama_konsumen',
                'reference' => 'id_konsumen',
                'label' => 'Konsumen',
            ],
            'bank_id' => [
                'table' => 'banks',
                'display' => 'bank',
                'reference' => 'id_bank',
                'label' => 'Bank',
            ],
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getExportImportActions(),
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
