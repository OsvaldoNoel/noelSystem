<?php

namespace App\Livewire\Tenant\Config\Cajas;

use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; 
use Livewire\Component;

class CajasController extends Component
{
    public $selected_id, $name, $ubi, $status, $estado;
    public $componentName, $datos;

    public function mount()
    {
        $this->componentName = 'Caja';
        $this->updateData();
    } 

    public function updateData()
    {
        $this->datos = Caja::query()
            ->select('id', 'name', 'ubi', 'status', 'estado')->get(); 
    } 
 
    public function statusFn($id)
    { 
        $record = Caja::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]);
        $this->updateData();
    }

    public function cancel()
    {
        $this->reset('selected_id', 'name', 'ubi');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected $messages = [
        'name.unique' => 'El nombre de caja ya fue registrado',
        'name.required' => 'El nombre del Caja es requerido',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',

        'ubi.unique' => 'La ubicación de caja ya fue registrada',
        'ubi.required' => 'La ubicación de caja es requerida',
        'ubi.min' => 'El campo debe tener al menos tres caracteres',
        'ubi.max' => 'El campo debe tener como maximo 50 caracteres',  
    ];

    public function addModal()
    {  
        $this->cancel();  
        $this->dispatch('showAddModal');
    }

    public function store()
    { 
        $this->validate([
            'name' => [
                'min:3',
                'max:50',
                'required',
                Rule::unique('cajas')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'ubi' => [
                'min:3',
                'max:50',
                'required',
                Rule::unique('cajas')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ], 
        ]);

        Caja::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $this->name,
            'ubi' => $this->ubi, 
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('msg-added');
        $this->updateData();
    }

    public function edit($id)
    {    
        $record = Caja::findOrFail($id);
        
        $this->selected_id = $id; 
		$this->name = $record-> name;
        $this->ubi = $record-> ubi;  
        
        $this->dispatch('showEditModal');
    }

    public function update()
    {
        $this->validate([
            'name' => [
                'min:3',
                'max:50',
                'required',
                Rule::unique('cajas')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'ubi' => [
                'min:3',
                'max:50',
                'required',
                Rule::unique('cajas')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ], 
        ]);

        if ($this->selected_id) {
            $record = Caja::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'ubi' => $this->ubi, 
            ]);

            $this->dispatch('closeModal');
            $this->dispatch('msg-updated');
            $this->updateData(); 
        }
    } 
 
    public function destroy(Caja $id)
    {
        if ($id) {
            $id->delete();
            $this->updateData();
            $this->dispatch('msg-deleted');
        }
    }
}
