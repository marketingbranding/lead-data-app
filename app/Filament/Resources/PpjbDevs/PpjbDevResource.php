<?php

namespace App\Filament\Resources\PpjbDevs;

use App\Filament\Resources\PpjbDevs\Pages\CreatePpjbDev;
use App\Filament\Resources\PpjbDevs\Pages\EditPpjbDev;
use App\Filament\Resources\PpjbDevs\Pages\ListPpjbDevs;
use App\Filament\Resources\PpjbDevs\Schemas\PpjbDevForm;
use App\Filament\Resources\PpjbDevs\Tables\PpjbDevsTable;
use App\Models\PpjbDev;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PpjbDevResource extends Resource
{
    protected static ?string $model = PpjbDev::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Proses Penjualan';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return PpjbDevForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpjbDevsTable::configure($table);
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
            'index' => ListPpjbDevs::route('/'),
            'create' => CreatePpjbDev::route('/create'),
            'edit' => EditPpjbDev::route('/{record}/edit'),
        ];
    }
}
