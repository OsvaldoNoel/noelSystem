<?php

namespace App\Livewire\Landlord;

use Livewire\Component;
use Livewire\Attributes\Layout;

class HomeLandlord extends Component
{
    #[Layout('layouts.landlord.theme')]
    public function render()
    {
        return view('livewire.landlord.home-landlord');
    }
}
