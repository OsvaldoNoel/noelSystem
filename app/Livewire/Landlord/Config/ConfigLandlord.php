<?php

namespace App\Livewire\Landlord\Config;

use Livewire\Component;
use Livewire\Attributes\Layout;

class ConfigLandlord extends Component
{
    //#[Layout('layouts.landlord.theme')]
    public function render()
    {
        return view('livewire.landlord.config.config-landlord');
    }
}
