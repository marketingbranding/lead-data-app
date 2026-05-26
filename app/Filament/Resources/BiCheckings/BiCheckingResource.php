<?php

namespace App\Filament\Resources\BiCheckings;

use App\Filament\Resources\BiCheckings\Pages\CreateBiChecking;
use App\Filament\Resources\BiCheckings\Pages\EditBiChecking;
use App\Filament\Resources\BiCheckings\Pages\ListBiCheckings;
use App\Filament\Resources\BiCheckings\Schemas\BiCheckingForm;
use App\Filament\Resources\BiCheckings\Tables\BiCheckingsTable;
use App\Models\BiChecking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BiCheckingResource extends Resource
{
    protected static ?string $model = BiChecking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Proses Penjualan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return BiCheckingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BiCheckingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBiCheckings::route('/'),
            'create' => CreateBiChecking::route('/create'),
            'edit' => EditBiChecking::route('/{record}/edit'),
        ];
    }
}
