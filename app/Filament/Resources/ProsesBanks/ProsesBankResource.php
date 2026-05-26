<?php

namespace App\Filament\Resources\ProsesBanks;

use App\Filament\Resources\ProsesBanks\Pages\CreateProsesBank;
use App\Filament\Resources\ProsesBanks\Pages\EditProsesBank;
use App\Filament\Resources\ProsesBanks\Pages\ListProsesBanks;
use App\Filament\Resources\ProsesBanks\Schemas\ProsesBankForm;
use App\Filament\Resources\ProsesBanks\Tables\ProsesBanksTable;
use App\Models\ProsesBank;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProsesBankResource extends Resource
{
    protected static ?string $model = ProsesBank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Proses Penjualan';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ProsesBankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProsesBanksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('kavling', fn (Builder $q) => $q->whereDoesntHave('konsumens', fn (Builder $q) => $q->where('status_cash', 'YA')));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProsesBanks::route('/'),
            'create' => CreateProsesBank::route('/create'),
            'edit' => EditProsesBank::route('/{record}/edit'),
        ];
    }
}
