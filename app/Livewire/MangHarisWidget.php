<?php

namespace App\Livewire;

use App\Services\MangHarisTips;
use Livewire\Component;

class MangHarisWidget extends Component
{
    public bool $muted = false;

    public string $tip = '';

    public string $contextual = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->muted = $user?->mangharis_muted ?? false;
        $this->contextual = MangHarisTips::contextual(request()->route()?->getName() ?? '') ?? '';
        $this->tip = MangHarisTips::randomNyeleneh();
    }

    public function showTip(): void
    {
        $this->tip = MangHarisTips::randomNyeleneh();
        $this->dispatch('tip-ready');
    }

    public function render()
    {
        return view('livewire.mang-haris-widget');
    }
}
