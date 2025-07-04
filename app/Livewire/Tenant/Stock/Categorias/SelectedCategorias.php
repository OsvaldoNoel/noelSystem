<?php

namespace App\Livewire\Tenant\Stock\Categorias;

use App\Models\Categoria;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedCategorias extends Component
{
    public $options;
    public function mount()
    {  
        $this->actualizarCategoria();
    }

    #[On('actualizarCategoria')]
    public function actualizarCategoria()
    {  
        $this->options = Categoria::query()->select('id','name',)->get()->toJson(); 
        $this->categorias_id(null);  
    }

    #[On('categoria_id')]
    public function categorias_id($id)
    {   
        $this->dispatch("valorData", $id);  
    }

}
