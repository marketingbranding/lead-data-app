<?php

namespace App\Filament\Resources\DanaTalangans;

use App\Filament\Resources\DanaTalangans\Pages\CreateDanaTalangan;
use App\Filament\Resources\DanaTalangans\Pages\EditDanaTalangan;
use App\Filament\Resources\DanaTalangans\Pages\ListDanaTalangans;
use App\Filament\Resources\DanaTalangans\Schemas\DanaTalanganForm;
use App\Filament\Resources\DanaTalangans\Tables\DanaTalangansTable;
use App\Models\DanaTalangan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DanaTalanganResource extends Resource
{
    protected static ?string $model = DanaTalangan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'id';

    protected static UnitEnum|string|null $navigationGroup = 'Laporan';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('admin-cabang')) {
            $query->where('cabang_id', auth()->user()->cabang_id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return DanaTalanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DanaTalangansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDanaTalangans::route('/'),
            'create' => CreateDanaTalangan::route('/create'),
            'edit' => EditDanaTalangan::route('/{record}/edit'),
        ];
    }
}
