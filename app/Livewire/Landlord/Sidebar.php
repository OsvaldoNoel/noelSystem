<?php

namespace App\Livewire\Landlord;

use Livewire\Component;

class Sidebar extends Component
{
    public $value, $bool;
                 
    public function mount()
    {
        $this->value = 1;  
        if ($this->value == 1 ? $this->bool = "false" : $this->bool = "true"); 
    }

    public function render()
    {
        return view('livewire.landlord.sidebar');
    }
}
