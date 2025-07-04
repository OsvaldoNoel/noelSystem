<?php

namespace App\Livewire\Tenant\Stock\Subcategorias;

use App\Models\Subcategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class SubcategoriaController extends Component
{
    public $selected_id, $categoria_id, $name, $status;
    public $componentName; 

    public function mount()
    {
        $this->componentName = 'Subcategoria'; 
        $this->updateTable();  
    }

    #[On('actualizarCategoria')]
    public function updateTable()
    {  
        $datos = Subcategoria::query()
            ->select(
                'subcategorias.id', 
                'subcategorias.status',
                'subcategorias.name', 
                'categorias.name as categoria_name',
                ) 
                ->join('categorias', 'subcategorias.categoria_id', '=', 'categorias.id')
                ->get()->toJson();
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
		$this->reset(['selected_id', 'categoria_id', 'name','status']);
    }

    #[On('addSubcategoria')]
    public function addSubcategoria()
    {
        $this->resetInput();
        $this->dispatch('categoria_id', $this->categoria_id);
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'name.required' => 'El nombre de la Subcategoria es requerido',
        'name.unique' => 'Una subcategoria con ese nombre ya fue registrada',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',
        'categoria_id.required' => 'Debe seleccionar una Categoría',
    ];

    public function store()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('subcategorias')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        'categoria_id' => 'required',
        ]);

        Subcategoria::create([ 
			'name' => $this-> name,
            'categoria_id' => $this-> categoria_id,
            'tenant_id' => Auth::user()->tenant_id
        ]);
        
        $this->resetInput();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('msg-added');
        $this->updateTable();
    }

    #[On('statusSubcategoria')]  
    public function status($id)
    {  
        $record = Subcategoria::findOrFail($id);
        if ($record->status == 1) { 
            $record->update(['status' => 0]);
        }else{
            $record->update(['status' => 1]);
        }    
    }
 
    #[On('editSubcategoria')]  
    public function edit($id)
    {  
        $this->cancel();
        $record = Subcategoria::findOrFail($id);
        $this->selected_id = $id; 
		$this->name = $record-> name;
        $this->categoria_id = $record-> categoria_id; 
        
        $this->dispatch('categoria_id', $this->categoria_id);
        $this->dispatch('showModal', $this->componentName);
    }

    #[On('selectedCategoria')]  
    public function selectedCategoria($id)
    {   
        $this->categoria_id = $id;   
    }

    public function update()
    {
        $this->validate([
		'name' => [
            'required', 'min:3', 'max:50',
                Rule::unique('subcategorias')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
        'categoria_id' => 'required',
        ]);

        if ($this->selected_id) {
			$record = Subcategoria::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
            'categoria_id' => $this-> categoria_id
            ]);

            $this->resetInput();
            $this->dispatch('closeModal', $this->componentName);
			$this->dispatch('msg-updated');
            $this->updateTable(); 
        }
    }

    #[On('deleteSubcategoria')] 
    public function destroy(Subcategoria $id)
    { 
        if ($id) {
            $id->delete();
        }
        $this->updateTable();
        $this->dispatch('msg-deleted');
    }
}
