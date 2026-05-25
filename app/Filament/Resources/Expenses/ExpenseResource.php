<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Filament\Resources\Expenses\Schemas\ExpenseForm;
use App\Filament\Resources\Expenses\Tables\ExpensesTable;
use App\Models\Expense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?string $recordTitleAttribute = 'nama_pengeluaran';

    protected static UnitEnum|string|null $navigationGroup = 'Keuangan';

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
        return ExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpensesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }
}
