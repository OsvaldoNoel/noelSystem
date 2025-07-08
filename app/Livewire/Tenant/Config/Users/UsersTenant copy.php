<?php

namespace App\Livewire\Tenant\Config\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class UsersTenant extends Component
{
    public $selected_id, $username, $ci, $name, $lastname, $phone, $address, $barrio, $city, $email; 
    public $componentName; 

    public function mount()
    {
        $this->componentName = 'Usuario'; 
        $this->updateTable();  
    }

    public function updateTable()
    {  
        $datos = User::query()
            ->where('tenant_id', Auth::user()->tenant_id)
            ->select('id','ci','name','lastname','phone','address','barrio','city','email','status') 
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
		$this->reset(['selected_id','username','ci','name','lastname','phone','email','address','barrio','city']);
    }

    #[On('addUsuario')]
    public function addUser()
    { 
        $this->resetInput(); 
        $this->dispatch('showModal', $this->componentName);
    }

    protected $messages = [
        'ci.required' => 'El Nro. de cedula de Identidad es requerido',
        'ci.integer' => 'El Nro. de C.I. debe ser un numero entero',
        'ci.unique' => 'Un Usuario con este Nro. de C.I ya fue registrado',
        'ci.min' => 'El Nro. de C.I. debe tener al menos 6 caracteres',
        'ci.max' => 'El Nro. de C.I. debe tener como maximo 10 caracteres',

        'name.required' => 'El nombre de Usuario es requerido', 
        'name.min' => 'El nombre debe tener al menos 3 caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres', 

        'lastname.required' => 'El apellido del Usuario es requerido', 
        'lastname.min' => 'El apellido debe tener al menos 3 caracteres',
        'lastname.max' => 'El apellido debe tener como maximo 50 caracteres',

        'phone.required' => 'El telefono del Usuario es requerido', 
        'phone.min' => 'El telefono debe tener al menos 9 caracteres',
        'phone.max' => 'El telefono debe tener como maximo 50 caracteres',

        'email.required' => 'El email del Usuario es requerido',  
        'email.email' => 'El formato de email es invalido',
        'email.unique' => 'Un Usuario con este email ya fue registrado',

        'address.required' => 'El telefono del Usuario es requerido', 
        'address.min' => 'El telefono debe tener al menos 5 caracteres',
        'address.max' => 'El telefono debe tener como maximo 100 caracteres',

        'barrio.required' => 'El telefono del Usuario es requerido', 
        'barrio.min' => 'El telefono debe tener al menos 5 caracteres',
        'barrio.max' => 'El telefono debe tener como maximo 50 caracteres',

        'city.required' => 'El telefono del Usuario es requerido', 
        'city.min' => 'El telefono debe tener al menos 5 caracteres',
        'city.max' => 'El telefono debe tener como maximo 50 caracteres',
    ];

    public function store()
    {
        $this->validate([
		'ci' => ['required','integer','unique:users','min:6'], //,'max:10'
        'name' => ['required', 'min:3', 'max:50'],
        'lastname' => ['required', 'min:3', 'max:50'],
        'phone' => ['required', 'min:9', 'max:50'],
        'email' => ['required', 'email','unique:users'],
        'address' => ['required', 'min:5', 'max:100'],
        'barrio' => ['required', 'min:5', 'max:50'],
        'city' => ['required', 'min:5', 'max:50'],
        ]);

        
        User::create([ 
            'tenant_id' => Auth::user()->tenant_id,
            'ci' => $this-> ci,
            'username' => $this-> ci . Auth::user()->tenant_id,
			'name' => $this-> name,
            'lastname' => $this-> lastname,
            'phone' => $this-> phone,            
            'email' => $this-> email,
            'address' => $this-> address,
            'barrio' => $this-> barrio,
            'city' => $this-> city,
            
            'password' => bcrypt('12345678')
        ]);
        
        $this->resetInput();
		$this->dispatch('closeModal', $this->componentName);
        $this->dispatch('msg-added');
        $this->updateTable();
    }

    #[On('statusUsuario')]  
    public function status($id)
    {  
        $record = User::findOrFail($id);
        if ($record->status == 1) { 
            $record->update(['status' => 0]);
        }else{
            $record->update(['status' => 1]);
        }    
    }
 
    #[On('editUsuario')]  
    public function edit($id)
    {  
        $this->cancel();
        $record = User::findOrFail($id);
        $this->selected_id = $id;
        $this->ci = $record-> ci; 
		$this->name = $record-> name;
        $this->lastname = $record-> lastname;
        $this->phone = $record-> phone; 
        $this->email = $record-> email;
        $this->address = $record-> address;
        $this->barrio = $record-> barrio; 
        $this->city = $record-> city; 
         
        $this->dispatch('showModal', $this->componentName);
    } 

    public function update()
    {
        $this->validate([
		'ci' => [
            'required','integer', 'min:6', //,'max:10'
            Rule::unique('users')->ignore($this->selected_id),
            ],  
        'email' => [
            'required', 'email',
            Rule::unique('users')->ignore($this->selected_id),
            ], 
        
        'name' => ['required', 'min:3', 'max:50'],
        'lastname' => ['required', 'min:3', 'max:50'],
        'phone' => ['required', 'min:9', 'max:50'],
        'address' => ['required', 'min:5', 'max:100'],
        'barrio' => ['required', 'min:5', 'max:50'],
        'city' => ['required', 'min:5', 'max:50'],

        ]);

        if ($this->selected_id) {
			$record = User::find($this->selected_id);

            if($this->ci != $record-> ci){
                $username = $this-> ci . Auth::user()->tenant_id;
                $password = bcrypt('12345678'); 
            }else{
                $username = $record-> username;
                $password = $record-> password; 
            };

            $record->update([  
            'ci' => $this-> ci,
            'name' => $this-> name,
            'lastname' => $this-> lastname,
            'phone' => $this-> phone,            
            'email' => $this-> email,
            'address' => $this-> address,
            'barrio' => $this-> barrio,
            'city' => $this-> city,

            'username' => $username,
            'password' => $password,

            ]);

            $this->resetInput();
            $this->dispatch('closeModal', $this->componentName);
			$this->dispatch('msg-updated');
            $this->updateTable(); 
        }
    }

    #[On('deleteUsuario')] 
    public function destroy(User $id)
    { 
        if ($id) {
            $id->delete();
        }
        $this->updateTable();
        $this->dispatch('msg-deleted');
    }
}
