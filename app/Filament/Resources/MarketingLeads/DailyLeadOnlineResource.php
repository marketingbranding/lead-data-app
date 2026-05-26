<?php

namespace App\Filament\Resources\MarketingLeads;

use App\Filament\Resources\MarketingLeads\Pages\CreateDailyLeadOnline;
use App\Filament\Resources\MarketingLeads\Pages\EditDailyLeadOnline;
use App\Filament\Resources\MarketingLeads\Pages\ListDailyLeadOnlines;
use App\Filament\Resources\MarketingLeads\Schemas\DailyLeadOnlineForm;
use App\Filament\Resources\MarketingLeads\Tables\DailyLeadOnlinesTable;
use App\Models\DailyLeadOnline;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DailyLeadOnlineResource extends Resource
{
    protected static ?string $model = DailyLeadOnline::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $recordTitleAttribute = 'id';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Marketing Leads';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return DailyLeadOnlineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyLeadOnlinesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDailyLeadOnlines::route('/'),
            'create' => CreateDailyLeadOnline::route('/create'),
            'edit' => EditDailyLeadOnline::route('/{record}/edit'),
        ];
    }
}
