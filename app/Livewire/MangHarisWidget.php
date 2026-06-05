<?php

namespace App\Livewire;

use App\Services\MangHarisTips;
use Livewire\Component;

class MangHarisWidget extends Component
{
    public bool $muted = false;

    public string $tip = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->muted = $user?->mangharis_muted ?? false;
        $this->pickTip();
    }

    public function showTip(): void
    {
        $this->pickTip();
        $this->dispatch('tip-ready');
    }

    private function pickTip(): void
    {
        $this->tip = random_int(0, 1)
            ? MangHarisTips::randomContextual()
            : MangHarisTips::randomNyeleneh();
    }

    public function render(): mixed
    {
        if ($this->muted) {
            return '<div style="display:none;"></div>';
        }

        return view('livewire.mang-haris-widget');
    }
}
