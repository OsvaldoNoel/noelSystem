<?php

namespace App\Livewire\Tenant\Stock\Marcas;

use App\Models\Marca;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedMarcas extends Component
{
    public $options;
    public function mount()
    {  
        $this->actualizarMarca();
    }

    #[On('actualizarMarca')]
    public function actualizarMarca()
    {  
        $this->options = Marca::query()->select('id','name',)->get()->toJson(); 
        $this->marcas_id(null);  
    }

    #[On('marca_id')]
    public function marcas_id($id)
    {   
        $this->dispatch("valorData", $id);  
    }
}
