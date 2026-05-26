<?php

namespace App\Filament\Resources\MarketingLeads;

use App\Filament\Resources\MarketingLeads\Pages\CreateCampaign;
use App\Filament\Resources\MarketingLeads\Pages\EditCampaign;
use App\Filament\Resources\MarketingLeads\Pages\ListCampaigns;
use App\Filament\Resources\MarketingLeads\Schemas\CampaignForm;
use App\Filament\Resources\MarketingLeads\Tables\CampaignsTable;
use App\Models\Campaign;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $recordTitleAttribute = 'campaign_id';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Marketing Leads';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return CampaignForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampaignsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCampaigns::route('/'),
            'create' => CreateCampaign::route('/create'),
            'edit' => EditCampaign::route('/{record}/edit'),
        ];
    }
}
