<?php

namespace App\Livewire\Tenant\Proveedors;

use App\Models\Proveedor;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedProveedor extends Component
{
    public $options;
    public function mount()
    {
        $this->actualizarProveedor();
        $this->dispatch('component-SelectedProveedor-ready'); // Notifica que está listo
    } 

    #[On('actualizarProveedor')]
    public function actualizarProveedor()
    {
        $this->options = Proveedor::query()->select('id', 'name', 'ruc', 'dv')->get()->toJson();
        $this->proveedors_id(null);
    }

    #[On('proveedor_id')]
    public function proveedors_id($id)
    {
        $this->dispatch("valorData", $id);
    }
}
