<?php

namespace App\Filament\Resources\DanaTalangans\Pages;

use App\Filament\Resources\DanaTalangans\DanaTalanganResource;
use Carbon\Carbon;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDanaTalangan extends EditRecord
{
    protected static string $resource = DanaTalanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['tgl_bbg_due'] = Carbon::parse($data['tgl_akad'])->addYear()->format('Y-m-d');

        return $data;
    }
}
