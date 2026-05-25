<?php

namespace App\Filament\Resources\Pemberkasans;

use App\Filament\Resources\Pemberkasans\Pages\CreatePemberkasan;
use App\Filament\Resources\Pemberkasans\Pages\EditPemberkasan;
use App\Filament\Resources\Pemberkasans\Pages\ListPemberkasans;
use App\Filament\Resources\Pemberkasans\Schemas\PemberkasanForm;
use App\Filament\Resources\Pemberkasans\Tables\PemberkasansTable;
use App\Models\Pemberkasan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PemberkasanResource extends Resource
{
    protected static ?string $model = Pemberkasan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Proses Penjualan';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PemberkasanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PemberkasansTable::configure($table);
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
            'index' => ListPemberkasans::route('/'),
            'create' => CreatePemberkasan::route('/create'),
            'edit' => EditPemberkasan::route('/{record}/edit'),
        ];
    }
}
