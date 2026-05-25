<?php

namespace App\Filament\Resources\Basts;

use App\Filament\Resources\Basts\Pages\CreateBast;
use App\Filament\Resources\Basts\Pages\EditBast;
use App\Filament\Resources\Basts\Pages\ListBasts;
use App\Filament\Resources\Basts\Schemas\BastForm;
use App\Filament\Resources\Basts\Tables\BastsTable;
use App\Models\Bast;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class BastResource extends Resource
{
    protected static ?string $model = Bast::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Proses Penjualan';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return BastForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BastsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('kavling.konsumens');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBasts::route('/'),
            'create' => CreateBast::route('/create'),
            'edit' => EditBast::route('/{record}/edit'),
        ];
    }
}
