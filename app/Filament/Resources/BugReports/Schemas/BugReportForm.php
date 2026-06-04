<?php

namespace App\Filament\Resources\BugReports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BugReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Textarea::make('deskripsi')
                    ->required()
                    ->rows(4),
                Select::make('prioritas')
                    ->required()
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                        'kritis' => 'Kritis',
                    ]),
                Select::make('status')
                    ->required()
                    ->options([
                        'baru' => 'Baru',
                        'dibaca' => 'Dibaca',
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ]),
                Textarea::make('catatan_admin')
                    ->label('Catatan Admin')
                    ->rows(3),
            ]);
    }
}
