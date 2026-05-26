<?php

namespace App\Filament\Resources\DanaTalangans\Pages;

use App\Filament\Resources\DanaTalangans\DanaTalanganResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreateDanaTalangan extends CreateRecord
{
    protected static string $resource = DanaTalanganResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tgl_bbg_due'] = Carbon::parse($data['tgl_akad'])->addYear()->format('Y-m-d');

        if (auth()->user()?->hasRole('admin-cabang')) {
            $data['cabang_id'] = auth()->user()->cabang_id;
        }

        return $data;
    }
}
