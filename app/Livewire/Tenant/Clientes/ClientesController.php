<?php

namespace App\Livewire\Tenant\Clientes;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class ClientesController extends Component
{
    public $selected_id,  $name, $ruc, $dv, $adress, $phone, $barrio, $city, $email, $status;
    public $componentName = 'Cliente';
    public $search = '';
    public $datos;

    public function mount()
    {
        $this->updateTable();
    }

    public function updateTable()
    {
        $this->datos = Cliente::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('ruc', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->select(['id', 'name', 'ruc', 'dv', 'barrio', 'city', 'adress', 'phone', 'email', 'status'])
            ->get();

        $this->dispatch('tableUpdated', datos: $this->datos);
    }

    #[On('statusCliente')]
    public function status($id)
    {
        $record = Cliente::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]);
    }

    public function cancel()
    {
        $this->reset(['selected_id', 'name', 'ruc', 'dv', 'barrio', 'city', 'adress', 'phone', 'email', 'status']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected $messages = [
        'name.required' => 'El nombre del Cliente es requerido',
        'name.min' => 'El nombre debe tener al menos tres caracteres',
        'name.max' => 'El nombre debe tener como maximo 50 caracteres',

        'ruc.required' => 'El RUC o CI del Cliente es requerido',
        'ruc.unique' => 'Un Cliente con este RUC ya fue registrado',
        'ruc.min' => 'El RUC debe tener al menos seis caracteres',
        'ruc.max' => 'El RUC debe tener como maximo 20 caracteres',

        'dv.max' => 'El DV solo puede ser de un dígito',

        'phone.required' => 'El telefono del Cliente es requerido',
        'phone.min' => 'El teléfono debe tener al menos seis caracteres',
        'phone.max' => '50 caracteres el maximo permitido para este campo',

        'email.email' => 'El formato debe ser de tipo e-mail',
        'email.min' => 'El e-mail debe tener al menos seis caracteres',
        'email.max' => '50 caracteres el maximo permitido para este campo',

        'barrio.min' => 'El Barrio debe tener al menos tres caracteres',
        'barrio.max' => '50 caracteres el maximo permitido para este campo',

        'city.min' => 'La ciudad debe tener al menos tres caracteres',
        'city.max' => '50 caracteres el maximo permitido para este campo',

        'adress.min' => 'La Dirección debe tener al menos tres caracteres',
        'adress.max' => '50 caracteres el maximo permitido para este campo',
    ];

    public function store()
    {
        $this->validate([
            'name' => ['required', 'min:3', 'max:50'],
            'ruc' => [
                'min:6',
                'max:20',
                'required',
                Rule::unique('clientes')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'dv' => 'max:1',
            'phone' => ['required', 'min:6', 'max:50'],
            'email' => ['nullable', 'email', 'min:6', 'max:50'],
            'barrio' => ['nullable', 'min:3', 'max:50'],
            'city' => ['nullable', 'min:3', 'max:50'],
            'adress' => ['nullable', 'min:3', 'max:100'],
        ]);

        Cliente::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $this->name,
            'ruc' => $this->ruc,
            'dv' => $this->dv,
            'barrio' => $this->barrio,
            'city' => $this->city,
            'adress' => $this->adress,
            'phone' => $this->phone,
            'email' => $this->email,
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('msg-added');
        $this->updateTable();
    }

    public function update()
    {
        $this->validate([
            'ruc' => [
                'required',
                'min:6',
                'max:20',
                Rule::unique('clientes')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'name' => ['required', 'min:3', 'max:50'],
            'dv' => 'max:1',
            'phone' => ['required', 'min:6', 'max:50'],
            'email' => ['email', 'min:6', 'max:50'],
            'barrio' => ['min:3', 'max:50'],
            'city' => ['min:3', 'max:50'],
            'adress' => ['min:3', 'max:100'],
        ]);

        if ($this->selected_id) {
            $record = Cliente::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'ruc' => $this->ruc,
                'dv' => $this->dv,
                'barrio' => $this->barrio,
                'city' => $this->city,
                'adress' => $this->adress,
                'phone' => $this->phone,
                'email' => $this->email,
            ]);

            $this->dispatch('closeModal');
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
