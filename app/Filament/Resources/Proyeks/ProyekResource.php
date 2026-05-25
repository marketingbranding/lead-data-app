<?php

namespace App\Filament\Resources\Proyeks;

use App\Filament\Resources\Proyeks\Pages\CreateProyek;
use App\Filament\Resources\Proyeks\Pages\EditProyek;
use App\Filament\Resources\Proyeks\Pages\ListProyeks;
use App\Filament\Resources\Proyeks\Schemas\ProyekForm;
use App\Filament\Resources\Proyeks\Tables\ProyeksTable;
use App\Models\Proyek;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProyekResource extends Resource
{
    protected static ?string $model = Proyek::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'nama_proyek';

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Proyek';

    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('super-admin');
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('super-admin');
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('super-admin');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasRole('super-admin');
    }

    public static function form(Schema $schema): Schema
    {
        return ProyekForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProyeksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProyeks::route('/'),
            'create' => CreateProyek::route('/create'),
            'edit' => EditProyek::route('/{record}/edit'),
        ];
    }
}
