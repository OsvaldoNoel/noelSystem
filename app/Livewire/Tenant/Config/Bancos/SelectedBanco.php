<?php

namespace App\Livewire\Tenant\Config\Bancos;

use App\Models\Banco;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedBanco extends Component
{
    public $options;

    public function mount()
    {
        $this->actualizarBanco();
    }

    #[On('actualizarBanco')]
    public function actualizarBanco()
    {
        $this->options = Banco::query()
            ->select(
                'bancos.id',
                'entidads.nombre as nombre'
            )
            ->join('entidads', 'bancos.entidad_id', '=', 'entidads.id')
            ->get()->toJson();
            
        $this->bancos_id(null);
    }

    #[On('banco_id')]
    public function bancos_id($id)
    {
        $this->dispatch("valorData", $id);
    }
}
