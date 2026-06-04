<?php

namespace App\Filament\Resources\Expenses\Pages;

use App\Filament\Actions\HasCreateTutupAction;
use App\Filament\Resources\Expenses\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    use HasCreateTutupAction;

    protected static string $resource = ExpenseResource::class;
}
