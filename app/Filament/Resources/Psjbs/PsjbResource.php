<?php

namespace App\Filament\Resources\Psjbs;

use App\Filament\Resources\Psjbs\Pages\CreatePsjb;
use App\Filament\Resources\Psjbs\Pages\EditPsjb;
use App\Filament\Resources\Psjbs\Pages\ListPsjbs;
use App\Filament\Resources\Psjbs\Schemas\PsjbForm;
use App\Filament\Resources\Psjbs\Tables\PsjbsTable;
use App\Models\Psjb;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PsjbResource extends Resource
{
    protected static ?string $model = Psjb::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Proses Penjualan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PsjbForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PsjbsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPsjbs::route('/'),
            'create' => CreatePsjb::route('/create'),
            'edit' => EditPsjb::route('/{record}/edit'),
        ];
    }
}
