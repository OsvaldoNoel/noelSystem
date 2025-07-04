<?php

namespace App\Livewire\Tenant\Stock\Presentations;

use App\Models\Presentation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class PresentationController extends Component
{
    public $selected_id, $name;
    public $componentName; 

    public function mount()
    {
        $this->componentName = 'Presentation'; 
        $this->updateTable();  
    }

    public function updateTable()
    {  
        $datos = Presentation::all()->toJson(); 
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

    #[On('addPresentation')]
    public function addPresentation()
    {
        $this->resetInput();
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'name.required' => 'El nombre de la Presentation es requerido',
        'name.unique' => 'Una presentación con ese nombre ya fue registrada',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',
    ];

    public function store()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('presentations')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        ]);

        Presentation::create([ 
			'name' => $this-> name,
            'tenant_id' => Auth::user()->tenant_id,
        ]);
        
        $this->resetInput();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('msg-added');
        $this->updateTable();
    }
 
    #[On('editPresentation')]  
    public function edit($id)
    {  
        $this->cancel();
        $record = Presentation::findOrFail($id);
        $this->selected_id = $id; 
		$this->name = $record-> name;

        $this->dispatch('showModal', $this->componentName);
    }

    public function update()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('presentations')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        ]);

        if ($this->selected_id) {
			$record = Presentation::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->dispatch('closeModal', $this->componentName);
			$this->dispatch('msg-updated');
            $this->updateTable(); 
        }
    }

    #[On('deletePresentation')] 
    public function destroy(Presentation $id)
    { 
        if ($id) {
            $id->delete();
        }
        $this->updateTable();
        $this->dispatch('msg-deleted');
    }
}
