<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Cabang;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('roles')
                    ->label('Roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
                Select::make('cabang_id')
                    ->label('Cabang')
                    ->options(Cabang::orderBy('urutan')->pluck('nama', 'id'))
                    ->native(false),
            ]);
    }
}
