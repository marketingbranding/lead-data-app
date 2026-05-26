<?php

namespace App\Filament\Pages;

use App\Filament\Resources\BiCheckings\BiCheckingResource;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ProsesPenjualan extends Page
{
    protected static ?string $navigationLabel = 'Proses Penjualan';

    protected string $view = 'filament.pages.group-page';

    protected static UnitEnum|string|null $navigationGroup = 'Penjualan & Marketing';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 1;

    public function mount(): void
    {
        $this->redirect(BiCheckingResource::getUrl());
    }
}
