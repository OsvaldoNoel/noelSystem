<?php

namespace App\Livewire\Tenant\Config\Bancos;

use App\Models\Entidad;
use Livewire\Attributes\On;
use App\Models\Banco;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BancosController extends Component
{
    public $selected_id, $entidad_id, $titular, $cuenta, $tipoCta, $moneda, $sobregiro, $oficial, $phone, $status;
    public $componentName, $datos;
    public $options;

    public function mount()
    {
        $this->componentName = 'Banco';
        $this->updateData();
    }

    public function updateData()
    {
        $this->datos = Banco::query()
            ->select(
                'bancos.id',
                'bancos.titular',
                'bancos.tipoCta',
                'bancos.cuenta',
                'bancos.moneda',
                'bancos.sobregiro',
                'bancos.oficial',
                'bancos.phone',
                'bancos.status',

                'entidads.nombre as entidad_nombre',
                'entidads.image as entidad_image',
                'entidads.color as entidad_color',
            )
            ->join('entidads', 'bancos.entidad_id', '=', 'entidads.id')
            ->get();
    }

    public function actualizarEntidad($id)
    {
        $this->options = Entidad::query()->select('id', 'nombre')
            ->where('status', '=', 1)
            ->get()->toJson();
        $this->dispatch('destroyTomselec');
        $this->dispatch("valorData", $id[0]);
    }


    #[On('selectedEntidad')]
    public function selectedEntidad($id)
    {
        $this->entidad_id = $id;
    }

    public function statusFn($id)
    {
        $record = Banco::findOrFail($id);
        ($record->status == 1) ? $record->update(['status' => 0]) : $record->update(['status' => 1]);
        $this->updateData();
    }

    public function cancel()
    {
        $this->reset('selected_id', 'entidad_id', 'titular', 'cuenta', 'tipoCta', 'moneda', 'sobregiro', 'oficial', 'phone');
        $this->resetErrorBag();
        $this->resetValidation();
        $this->updateData();
    }

    protected $messages = [
        'entidad_id.required' => 'El nombre del Banco es requerido',

        'titular.required' => 'El nombre del Titular es requerido',
        'titular.min' => 'El campo debe tener al menos tres caracteres',
        'titular.max' => 'El campo debe tener como maximo 50 caracteres',

        'cuenta.unique' => 'La cuenta ya fue registrada',
        'cuenta.required' => 'El numero de cuenta es requerido',
        'cuenta.min' => 'La cuenta debe tener al menos tres caracteres',
        'cuenta.max' => 'La cuenta debe tener como maximo 20 caracteres',

        'moneda.required' => 'La denominación de moneda es requerida',
        'moneda.min' => 'La moneda debe tener al menos tres caracteres',
        'moneda.max' => 'La moneda debe tener como maximo 20 caracteres',

        'sobregiro.required' => 'El valor de sobregiro es requerido',

        'oficial.min' => 'El nombre del oficial debe tener al menos tres caracteres',
        'oficial.max' => 'El nombre del oficial debe tener como maximo 50 caracteres',

        'phone.min' => 'El teléfono debe tener al menos seis caracteres',
        'phone.max' => '50 caracteres el maximo permitido para este campo',
    ];

    public function addModal()
    {
        $this->cancel();
        $this->tipoCta = 'Cuenta Corriente';
        $this->moneda = 'Guaranies';
        $this->sobregiro = 0;
        $this->dispatch('showAddModal');
    }

    public function store()
    {
        $this->validate([
            'entidad_id' => 'required',
            'titular' => ['required', 'min:3', 'max:50'],
            'cuenta' => [
                'min:3',
                'max:20',
                'required',
                Rule::unique('bancos')->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'moneda' => ['required', 'min:6', 'max:20'],
            'sobregiro' => 'required',
            'oficial' => ['nullable', 'min:3', 'max:50'],
            'phone' => ['nullable', 'min:6', 'max:50'],
        ]);

        Banco::create([
            'tenant_id' => Auth::user()->tenant_id,
            'entidad_id' => $this->entidad_id,
            'titular' => $this->titular,
            'cuenta' => $this->cuenta,
            'tipoCta' => $this->tipoCta,
            'moneda' => $this->moneda,
            'sobregiro' => $this->sobregiro,
            'oficial' => $this->oficial,
            'phone' => $this->phone,
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('msg-added');
        $this->updateData();
    }

    public function edit($id)
    {
        $record = Banco::findOrFail($id);

        $this->selected_id = $id;
        $this->entidad_id = $record->entidad_id;
        $this->titular = $record->titular;
        $this->cuenta = $record->cuenta;
        $this->tipoCta = $record->tipoCta;
        $this->moneda = $record->moneda;
        $this->sobregiro = $record->sobregiro;
        $this->oficial = $record->oficial;
        $this->phone = $record->phone;

        $this->dispatch('showEditModal', $id);
        $this->dispatch('destroyTomselec');
    }

    public function update()
    {
        $this->validate([
            'entidad_id' => ['required'],
            'titular' => ['required', 'min:3', 'max:50'],
            'cuenta' => [
                'min:3',
                'max:20',
                'required',
                Rule::unique('bancos')->ignore($this->selected_id)->where(function ($query) {
                    $query->where('tenant_id', Auth::user()->tenant_id);
                })
            ],
            'moneda' => ['required', 'min:6', 'max:20'],
            'sobregiro' => 'required',
            'oficial' => ['nullable', 'min:3', 'max:50'],
            'phone' => ['nullable', 'min:6', 'max:50'],
        ]);

        if ($this->selected_id) {
            $record = Banco::find($this->selected_id);
            $record->update([
                'entidad_id' => $this->entidad_id,
                'titular' => $this->titular,
                'cuenta' => $this->cuenta,
                'tipoCta' => $this->tipoCta,
                'moneda' => $this->moneda,
                'sobregiro' => $this->sobregiro,
                'oficial' => $this->oficial,
                'phone' => $this->phone,
            ]);

            $this->dispatch('closeModal');
            $this->dispatch('msg-updated');
            $this->updateData();
        }
    }

    public function destroy(Banco $id)
    {
        if ($id) {
            $id->delete();
            $this->updateData();
            $this->dispatch('msg-deleted');
        }
    }
}
