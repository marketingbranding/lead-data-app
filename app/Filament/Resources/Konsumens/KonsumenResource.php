<?php

namespace App\Filament\Resources\Konsumens;

use App\Filament\Resources\Konsumens\Pages\CreateKonsumen;
use App\Filament\Resources\Konsumens\Pages\EditKonsumen;
use App\Filament\Resources\Konsumens\Pages\ListKonsumens;
use App\Filament\Resources\Konsumens\Schemas\KonsumenForm;
use App\Filament\Resources\Konsumens\Tables\KonsumensTable;
use App\Models\Konsumen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KonsumenResource extends Resource
{
    protected static ?string $model = Konsumen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $recordTitleAttribute = 'nama_konsumen';

    protected static UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return KonsumenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KonsumensTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKonsumens::route('/'),
            'create' => CreateKonsumen::route('/create'),
            'edit' => EditKonsumen::route('/{record}/edit'),
        ];
    }
}
