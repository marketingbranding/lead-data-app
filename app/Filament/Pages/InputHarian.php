<?php

namespace App\Filament\Pages;

use App\Livewire\InputHarianOfflineForm;
use App\Livewire\InputHarianOnlineForm;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class InputHarian extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static ?string $title = 'Input Harian';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static ?int $navigationSort = 7;

    public string $tab = 'offline';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Input Harian')
                    ->livewireProperty('tab')
                    ->contained(false)
                    ->tabs([
                        Tab::make('Offline')
                            ->key('offline')
                            ->icon('heroicon-o-clipboard-document')
                            ->schema([
                                Livewire::make(InputHarianOfflineForm::class),
                            ]),
                        Tab::make('Online')
                            ->key('online')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Livewire::make(InputHarianOnlineForm::class),
                            ]),
                    ]),
            ]);
    }
}
