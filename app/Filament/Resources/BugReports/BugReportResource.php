<?php

namespace App\Filament\Resources\BugReports;

use App\Filament\Resources\BugReports\Pages\EditBugReport;
use App\Filament\Resources\BugReports\Pages\ListBugReports;
use App\Filament\Resources\BugReports\Schemas\BugReportForm;
use App\Filament\Resources\BugReports\Tables\BugReportsTable;
use App\Models\BugReport;
use BackedEnum;
use Filament\Resources\Resource;
use UnitEnum;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BugReportResource extends Resource
{
    protected static ?string $model = BugReport::class;

    protected static ?string $recordTitleAttribute = 'judul';

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 99;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBugAnt;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return BugReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BugReportsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBugReports::route('/'),
            'edit' => EditBugReport::route('/{record}/edit'),
        ];
    }
}
