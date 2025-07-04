<?php

namespace App\Livewire\Tenant\Finanzas\Aportantes;

use App\Models\Aportante;
use Livewire\Attributes\On;
use Livewire\Component;

class SelectedAportantes extends Component
{
    public $options;
    public function mount()
    {
        $this->actualizarAportante();
    }

    #[On('actualizarAportante')]
    public function actualizarAportante()
    {
        $this->options = Aportante::query()
            ->where('status', 1)
            ->select('id', 'name',)
            ->get()->toJson();
        $this->aportantes_id(null);
    }

    #[On('aportante_id')]
    public function aportantes_id($id)
    {
        $this->dispatch("valorData", $id);
    }
}
