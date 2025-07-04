<?php

namespace App\Livewire\Tenant\Stock\Categorias;

use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoriaController extends Component
{
    public $selected_id, $name, $status;
    public $componentName; 

    public function mount()
    {
        $this->componentName = 'Categoria'; 
        $this->updateTable();   
    }

    public function updateTable()
    {  
        $datos = Categoria::query()
            ->select('id','status','name')->get()->toJson();    
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
		$this->reset(['selected_id','name','status']);
    }

    #[On('addCategoria')]
    public function addCategoria()
    {
        $this->resetInput();
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'name.required' => 'El nombre de la Categoria es requerido',
        'name.unique' => 'Una categoria con ese nombre ya fue registrada',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',
    ];

    public function store()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('categorias')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        ]);

        Categoria::create([ 
			'name' => $this-> name,
            'tenant_id' => Auth::user()->tenant_id,
        ]);
        
        $this->resetInput();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('actualizarCategoria');
        $this->dispatch('msg-added'); 
        $this->updateTable();
    }

    #[On('statusCategoria')]  
    public function status($id)
    {  
        $record = Categoria::findOrFail($id);
        if ($record->status == 1) { 
            $record->update(['status' => 0]);
        }else{
            $record->update(['status' => 1]);
        }    
    }
 
    #[On('editCategoria')]  
    public function edit($id)
    {  
        $this->cancel();
        $record = Categoria::findOrFail($id);
        $this->selected_id = $id; 
		$this->name = $record-> name;

        $this->dispatch('showModal', $this->componentName);
    }

    public function update()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('categorias')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        ]);

        if ($this->selected_id) {
			$record = Categoria::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name
            ]);

            $this->resetInput();
            $this->dispatch('closeModal', $this->componentName);
            $this->dispatch('actualizarCategoria');
			$this->dispatch('msg-updated');
            $this->updateTable(); 
        }
    }

    #[On('deleteCategoria')] 
    public function destroy(Categoria $id)
    { 
        if ($id) {
            $id->delete();
            $this->updateTable();
            $this->dispatch('msg-deleted');
            $this->dispatch('actualizarCategoria');
        }
    }
}
