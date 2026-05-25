<?php

namespace App\Filament\Resources\LeadTimes;

use App\Filament\Resources\LeadTimes\Pages\CreateLeadTime;
use App\Filament\Resources\LeadTimes\Pages\EditLeadTime;
use App\Filament\Resources\LeadTimes\Pages\ListLeadTimes;
use App\Filament\Resources\LeadTimes\Schemas\LeadTimeForm;
use App\Filament\Resources\LeadTimes\Tables\LeadTimesTable;
use App\Models\LeadTime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LeadTimeResource extends Resource
{
    protected static ?string $model = LeadTime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'proses';

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 6;

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
        return LeadTimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadTimesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeadTimes::route('/'),
            'create' => CreateLeadTime::route('/create'),
            'edit' => EditLeadTime::route('/{record}/edit'),
        ];
    }
}
