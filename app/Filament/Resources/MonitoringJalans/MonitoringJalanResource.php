<?php

namespace App\Filament\Resources\MonitoringJalans;

use App\Filament\Resources\MonitoringJalans\Pages\CreateMonitoringJalan;
use App\Filament\Resources\MonitoringJalans\Pages\EditMonitoringJalan;
use App\Filament\Resources\MonitoringJalans\Pages\ListMonitoringJalans;
use App\Filament\Resources\MonitoringJalans\Schemas\MonitoringJalanForm;
use App\Filament\Resources\MonitoringJalans\Tables\MonitoringJalansTable;
use App\Models\MonitoringJalan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MonitoringJalanResource extends Resource
{
    protected static ?string $model = MonitoringJalan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $recordTitleAttribute = 'id';

    protected static UnitEnum|string|null $navigationGroup = 'Laporan';

    public static function form(Schema $schema): Schema
    {
        return MonitoringJalanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MonitoringJalansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMonitoringJalans::route('/'),
            'create' => CreateMonitoringJalan::route('/create'),
            'edit' => EditMonitoringJalan::route('/{record}/edit'),
        ];
    }
}
