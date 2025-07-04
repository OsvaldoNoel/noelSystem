<?php

namespace App\Livewire\Tenant\Stock\Subcategorias;

use App\Models\Subcategoria;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedSubcategorias extends Component
{
    public $options; 

    #[On('categoria_id')]
    public function categoria_id($id)
    {   
        $this->options = Subcategoria::where('categoria_id', $id)->select('id','name',)->get()->toJson();  
    }

    #[On('selectedCategoria')]
    public function selectedCategoria($id)
    {   
        $this->options = Subcategoria::where('categoria_id', $id)->select('id','name',)->get()->toJson();  
        $this->dispatch("selectedSubcategoria", null);
        $this->dispatch("valorData", null);
    } 

    #[On('subcategoria_id')]
    public function subcategoria_id($id)
    {   
        $this->dispatch("valorData", $id);  
    }
}
