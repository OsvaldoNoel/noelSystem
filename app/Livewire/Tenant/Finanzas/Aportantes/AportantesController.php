<?php

namespace App\Livewire\Tenant\Finanzas\Aportantes;

use App\Models\Aportante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class AportantesController extends Component
{
    public $selected_id, $name, $aporte, $status; 
    public $componentName, $datos;

    public function mount()
    {
        $this->componentName = 'Aportante'; 
        $this->updateTable();  
    } 
    
    #[On('updateSaldoAportante')] 
    public function updateTable()
    {  
        $this->datos = Aportante::query()
            ->select('id','name','aporte','status')->get()->toJson();    
        $this->dispatch($this->componentName, $this->datos);   
    } 

    #[On('statusAportante')] 
    public function status($id)
    {  
        $record = Aportante::findOrFail($id); 
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]); 
    }  

    public function cancel()
    {
		$this->reset(['selected_id','name','aporte','status']);
        $this->resetErrorBag();
        $this->resetValidation();
    }  

    protected $messages = [
        'name.required' => 'El nombre del Aportante es requerido',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',
    ];

    public function store()
    {  
        $this->validate([ 
        'name' => ['min:3', 'max:50', 'required',
                Rule::unique('aportantes')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ], 
        ]); 

        Aportante::create([ 
            'tenant_id' => Auth::user()->tenant_id,
			'name' => $this-> name, 
            'aporte' => 0, 
        ]);
        
		$this->dispatch('closeModal');
        $this->dispatch('msg-added');
        $this->updateTable(); 
    } 

    public function update()
    { 
        $this->validate([
		'name' => [
            'min:3', 'max:50', 'required',
                    Rule::unique('aportantes')->ignore($this->selected_id)->where(function ($query) {
                        $query->where('tenant_id', Auth::user()->tenant_id);
                    })
				], 
        ]);   
        
        if ($this->selected_id) {
			$record = Aportante::find($this->selected_id);
            $record->update([ 
                'name' => $this-> name,
            ]); 
            
            $this->dispatch('closeModal');
			$this->dispatch('msg-updated');
            $this->updateTable();   
        }
    } 
}
