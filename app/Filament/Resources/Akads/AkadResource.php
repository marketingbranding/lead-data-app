<?php

namespace App\Filament\Resources\Akads;

use App\Filament\Resources\Akads\Pages\CreateAkad;
use App\Filament\Resources\Akads\Pages\EditAkad;
use App\Filament\Resources\Akads\Pages\ListAkads;
use App\Filament\Resources\Akads\Schemas\AkadForm;
use App\Filament\Resources\Akads\Tables\AkadsTable;
use App\Models\Akad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AkadResource extends Resource
{
    protected static ?string $model = Akad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHandRaised;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Proses Penjualan';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return AkadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AkadsTable::configure($table);
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
            'index' => ListAkads::route('/'),
            'create' => CreateAkad::route('/create'),
            'edit' => EditAkad::route('/{record}/edit'),
        ];
    }
}
