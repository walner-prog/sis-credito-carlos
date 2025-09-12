<?php

namespace App\Livewire;

use Livewire\Component;


class Navigation extends Component
{
    public bool $open = false;

    public function toggleMenu()
    {
        $this->open = ! $this->open;
    }

    public function closeMenu()
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}