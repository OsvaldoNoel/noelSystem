<?php

namespace App\Livewire\Tenant\Stock\Presentations;

use App\Models\Presentation;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedPresentations extends Component
{
    public $options;
    public function mount()
    {  
        $this->actualizarPresentation();
    }

    #[On('actualizarPresentation')]
    public function actualizarPresentation()
    {  
        $this->options = Presentation::query()->select('id','name',)->get()->toJson(); 
        $this->presentations_id(null);  
    }

    #[On('presentation_id')]
    public function presentations_id($id)
    {   
        $this->dispatch("valorData", $id);  
    }
}
