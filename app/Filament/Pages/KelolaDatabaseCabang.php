<?php

namespace App\Filament\Pages;

use App\Models\Cabang;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class KelolaDatabaseCabang extends Page
{
    protected static ?string $navigationLabel = 'Database Cabang';

    protected string $view = 'filament.pages.kelola-database-cabang';

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static ?int $navigationSort = 10;

    public function getCabangs()
    {
        $user = auth()->user();

        if ($user->hasRole('super-admin')) {
            return Cabang::whereNotNull('google_sheet_id')
                ->orderBy('urutan')
                ->get();
        }

        return Cabang::where('id', $user->cabang_id)
            ->whereNotNull('google_sheet_id')
            ->get();
    }
}
