<?php

namespace App\Livewire\Tenant\Clientes;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ListaClientes extends Component
{   
    public $selected_id, $name, $ruc_number, $ruc_DV, $adress , $phone, $email, $status; 
    public $componentName, $datos;

    public function mount()
    {
        $this->componentName = 'Cliente'; 
        $this->updateTable();  
    }

    public function updateTable()
    {  
        $this->datos = Cliente::query()
            ->select('id','name','ruc_number','ruc_DV','adress','phone','email','status')->get()->toJson();    
        $this->dispatch($this->componentName, $this->datos);   
    } 

    #[On('statusCliente')]  
    public function status($id)
    {  
        $record = Cliente::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]); 
    }  
	
    public function cancel()
    {
		$this->reset(['selected_id', 'name', 'ruc_number', 'ruc_DV','adress','phone','email','status']);
        $this->resetErrorBag();
        $this->resetValidation();
    } 


    #[On('addCliente')]
    public function addCliente()
    { 
        $this->cancel();  
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'name.required' => 'El nombre del Cliente es requerido',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',

		'ruc_number.required' => 'El RUC o CI del Cliente es requerido',
		'ruc_number.unique' => 'Un Cliente con este RUC ya fue registrado',
        
		'phone.required' => 'El telefono del Cliente es requerido',  
    ];

    public function store()
    {  
        $this->validate([
        'name' => ['required', 'min:3', 'max:50'],
        'ruc_number' => [
            'required',
                Rule::unique('clientes')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
		'phone' => 'required',   
        ]); 

        Cliente::create([ 
            'tenant_id' => Auth::user()->tenant_id,
			'name' => $this-> name,
			'ruc_number' => $this-> ruc_number, 
			'ruc_DV' => $this-> ruc_DV,
			'adress' => $this-> adress,
			'phone' => $this-> phone,
			'email' => $this-> email, 
        ]);
        
        $this->cancel();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('msg-added');
        $this->updateTable();
    }
 
    #[On('editCliente')]
    public function edit($id)
    {
		$this->cancel();

        $record = Cliente::findOrFail($id); 
        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->ruc_number = $record-> ruc_number;
		$this->ruc_DV = $record-> ruc_DV;
		$this->adress = $record-> adress;
		$this->phone = $record-> phone;
		$this->email = $record-> email;  
        
        $this->dispatch('showModal', $this->componentName);  
    } 

    public function update()
    { 
        $this->validate([
		'ruc_number' => ['required',
                    Rule::unique('clientes')->ignore($this->selected_id)->where(function ($query) {
                        $query->where('tenant_id', Auth::user()->tenant_id);
                    })
				],
		'name' => ['required', 'min:3', 'max:50'],
		'phone' => 'required', 
        ]);  

        if ($this->selected_id) {
			$record = Cliente::find($this->selected_id);
            $record->update([ 
                'name' => $this-> name,
                'ruc_number' => $this-> ruc_number, 
                'ruc_DV' => $this-> ruc_DV,
                'adress' => $this-> adress,
                'phone' => $this-> phone,
                'email' => $this-> email, 
            ]); 
            $this->cancel();
            $this->dispatch('closeModal', $this->componentName);
			$this->dispatch('msg-updated');
            $this->updateTable(); 
        }
    }

    #[On('deleteCliente')]
    public function destroy(Cliente $id)
    {
        if ($id) { 
            $id->delete(); 
            $this->updateTable();
            $this->dispatch('msg-deleted');
        }
    }
}
