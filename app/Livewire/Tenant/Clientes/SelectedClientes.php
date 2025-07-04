<?php

namespace App\Livewire\Tenant\Clientes;

use App\Models\Cliente;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedClientes extends Component
{
    public $options;
    public function mount()
    {  
        $this->actualizarCliente();
    }

    #[On('actualizarCliente')]
    public function actualizarCliente()
    {  
        $this->options = Cliente::query()->select('id','name','ruc','dv')->get()->toJson(); 
        $this->clientes_id(null);  
    }

    #[On('cliente_id')]
    public function clientes_id($id)
    {   
        $this->dispatch("valorData", $id);  
    }
}
