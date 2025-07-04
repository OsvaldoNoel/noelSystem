<?php

namespace App\Livewire\Tenant\Stock\Marcas;

use App\Models\Marca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class MarcaController extends Component
{  
    public $selected_id, $name;
    public $componentName; 

    public function mount()
    {
        $this->componentName = 'Marca'; 
        $this->updateTable();  
    }

    public function updateTable()
    {  
        $datos = Marca::query()
            ->select('id','name')->get()->toJson();   
        $this->dispatch($this->componentName, $datos);  
    }
    
    public function cancel()
    {
        $this->resetInput();
        $this->resetErrorBag();
        $this->resetValidation();
    }
	
    private function resetInput()
    {		
		$this->reset(['selected_id','name']);
    }

    #[On('addMarca')]
    public function addMarca()
    {
        $this->resetInput();
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'name.required' => 'El nombre de la Marca es requerido',
        'name.unique' => 'Una marca con ese nombre ya fue registrada',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',
    ];

    public function store()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('marcas')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        ]);  

        Marca::create([ 
			'name' => $this-> name,
            'tenant_id' => Auth::user()->tenant_id,
        ]);
        
        $this->resetInput();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('msg-added');
        $this->updateTable();
    }
 
    #[On('editMarca')]  
    public function edit($id)
    {  
        $this->cancel();
        $record = Marca::findOrFail($id);
        $this->selected_id = $id; 
		$this->name = $record-> name;

        $this->dispatch('showModal', $this->componentName);
    }

    public function update()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('marcas')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        ]);

        if ($this->selected_id) {
			$record = Marca::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->dispatch('closeModal', $this->componentName);
			$this->dispatch('msg-updated');
            $this->updateTable(); 
        }
    }

    #[On('deleteMarca')] 
    public function destroy(Marca $id)
    { 
        if ($id) {
            $id->delete();
        }
        $this->updateTable();
        $this->dispatch('msg-deleted');
    }
}
