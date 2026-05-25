<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Role')
                    ->required()
                    ->maxLength(255),
                TextInput::make('guard_name')
                    ->label('Guard')
                    ->default('web')
                    ->required()
                    ->maxLength(255),
                Select::make('permissions')
                    ->label('Permissions')
                    ->multiple()
                    ->relationship('permissions', 'name')
                    ->preload()
                    ->searchable(),
            ]);
    }
}
