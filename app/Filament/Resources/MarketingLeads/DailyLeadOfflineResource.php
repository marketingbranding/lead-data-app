<?php

namespace App\Filament\Resources\MarketingLeads;

use App\Filament\Resources\MarketingLeads\Pages\EditDailyLeadOffline;
use App\Filament\Resources\MarketingLeads\Pages\ListDailyLeadOfflines;
use App\Filament\Resources\MarketingLeads\Schemas\DailyLeadOfflineForm;
use App\Filament\Resources\MarketingLeads\Tables\DailyLeadOfflinesTable;
use App\Models\DailyLeadOffline;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DailyLeadOfflineResource extends Resource
{
    protected static ?string $model = DailyLeadOffline::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocument;

    protected static ?string $recordTitleAttribute = 'id';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Marketing Leads';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return DailyLeadOfflineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyLeadOfflinesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDailyLeadOfflines::route('/'),
            'edit' => EditDailyLeadOffline::route('/{record}/edit'),
        ];
    }
}
