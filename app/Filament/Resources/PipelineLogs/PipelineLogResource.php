<?php

namespace App\Filament\Resources\PipelineLogs;

use App\Filament\Resources\PipelineLogs\Pages\CreatePipelineLog;
use App\Filament\Resources\PipelineLogs\Pages\EditPipelineLog;
use App\Filament\Resources\PipelineLogs\Pages\ListPipelineLogs;
use App\Filament\Resources\PipelineLogs\Schemas\PipelineLogForm;
use App\Filament\Resources\PipelineLogs\Tables\PipelineLogsTable;
use App\Models\PipelineLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PipelineLogResource extends Resource
{
    protected static ?string $model = PipelineLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'id_kavling';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?string $navigationParentItem = 'Proses Penjualan';

    protected static ?int $navigationSort = 8;

    public static function form(Schema $schema): Schema
    {
        return PipelineLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PipelineLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPipelineLogs::route('/'),
            'create' => CreatePipelineLog::route('/create'),
            'edit' => EditPipelineLog::route('/{record}/edit'),
        ];
    }
}
