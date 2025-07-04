<?php

namespace App\Livewire\Tenant\Finanzas\Aportes\Tabla;

use App\Models\Aporte;
use Livewire\Component;
use Livewire\Attributes\On;

class AportesTablaController extends Component
{
    public $selected_id, $aportante_id, $operacion, $destino, $monto, $detail, $saldo, $date ;
    public $componentName, $datos;

    public function mount()
    {
        $this->componentName = 'Aporte';
        $this->updateTable();
    }

    //#[On('aportanteDetalle')]
    public function updateTable()
    {  
        $this->datos = Aporte::query()
            //->where('aportes.aportante_id', $id)
            ->select(
                'aportes.id',
                'aportes.operacion',
                'aportes.monto',
                'aportes.detail',
                'aportes.saldo', 
                'aportes.date',

                'aportantes.name as aportante_name',
                'entidads.nombre as entidad_nombre',
            )
            ->join('aportantes', 'aportes.aportante_id', '=', 'aportantes.id')
            ->leftjoin('bancos', 'aportes.destino', '=', 'bancos.id')
            ->leftjoin('entidads', 'bancos.entidad_id', '=', 'entidads.id')
            ->get()->toJson();
        $this->dispatch($this->componentName, $this->datos);
    }

    public function cancel()
    {
        $this->reset(['selected_id', 'aportante_id', 'operacion', 'destino', 'monto', 'detail', 'saldo', 'date']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // protected $messages = [
    //     'name.required' => 'El nombre del Aporte es requerido',
    //     'name.min' => 'El nombre debe tener al menos tres caracteres',
    //     'name.max' => 'El nombre debe tener como maximo 50 caracteres',

    // 	'ruc.required' => 'El RUC o CI del Aporte es requerido',
    // 	'ruc.unique' => 'Un Aporte con este RUC ya fue registrado',
    //     'ruc.min' => 'El RUC debe tener al menos seis caracteres',
    //     'ruc.max' => 'El RUC debe tener como maximo 20 caracteres',

    //     'dv.max' => 'El DV solo puede ser de un dígito',

    // 	'phone.required' => 'El telefono del Aporte es requerido',
    //     'phone.min' => 'El teléfono debe tener al menos seis caracteres',
    //     'phone.max' => '50 caracteres el maximo permitido para este campo',  

    //     'email.email' => 'El formato debe ser de tipo e-mail',
    //     'email.min' => 'El e-mail debe tener al menos seis caracteres',
    //     'email.max' => '50 caracteres el maximo permitido para este campo', 

    //     'barrio.min' => 'El Barrio debe tener al menos tres caracteres',
    //     'barrio.max' => '50 caracteres el maximo permitido para este campo',

    //     'city.min' => 'La ciudad debe tener al menos tres caracteres',
    //     'city.max' => '50 caracteres el maximo permitido para este campo',

    //     'adress.min' => 'La Dirección debe tener al menos tres caracteres',
    //     'adress.max' => '50 caracteres el maximo permitido para este campo',
    // ];

    // public function store()
    // {  
    //     $this->validate([
    //     'name' => ['required', 'min:3', 'max:50'],
    //     'ruc' => ['min:6', 'max:20',
    //         'required',
    //             Rule::unique('aportes')->where(function ($query) {
    //                 $query->where('tenant_id', Auth::user()->tenant_id);
    //             })
    //         ],
    //     'dv' => 'max:1',
    // 	'phone' => ['required', 'min:6', 'max:50'],
    //     'email' => ['nullable','email', 'min:6', 'max:50'],
    //     'barrio' => ['nullable','min:3', 'max:50'],
    //     'city' => ['nullable','min:3', 'max:50'],
    //     'adress' => ['nullable','min:3', 'max:100'],
    //     ]); 

    //     Aporte::create([ 
    //         'tenant_id' => Auth::user()->tenant_id,
    // 		'name' => $this-> name,
    // 		'ruc' => $this-> ruc, 
    // 		'dv' => $this-> dv,
    //         'barrio' => $this-> barrio,
    //         'city' => $this-> city,
    // 		'adress' => $this-> adress,
    // 		'phone' => $this-> phone,
    // 		'email' => $this-> email, 
    //     ]);

    // 	$this->dispatch('closeModal');
    //     $this->dispatch('msg-added');
    //     $this->updateTable();
    // } 

    // public function update()
    // { 
    //     $this->validate([
    // 	'ruc' => ['required',
    //                 Rule::unique('aportes')->ignore($this->selected_id)->where(function ($query) {
    //                     $query->where('tenant_id', Auth::user()->tenant_id);
    //                 })
    // 			],
    // 	'name' => ['required', 'min:3', 'max:50'],
    // 	'dv' => 'max:1',
    // 	'phone' => ['required', 'min:6', 'max:50'],
    //     'email' => ['email', 'min:6', 'max:50'],
    //     'barrio' => ['min:3', 'max:50'],
    //     'city' => ['min:3', 'max:50'],
    //     'adress' => ['min:3', 'max:100'], 
    //     ]);   

    //     if ($this->selected_id) {
    // 		$record = Aporte::find($this->selected_id);
    //         $record->update([ 
    //             'name' => $this-> name,
    //             'ruc' => $this-> ruc, 
    //             'dv' => $this-> dv,
    //             'barrio' => $this-> barrio,
    //             'city' => $this-> city,
    //             'adress' => $this-> adress,
    //             'phone' => $this-> phone,
    //             'email' => $this-> email, 
    //         ]); 

    //         $this->dispatch('closeModal');
    // 		$this->dispatch('msg-updated');
    //         $this->updateTable(); 
    //     }
    // }

    // #[On('deleteAporte')]
    // public function destroy(Aporte $id)
    // {
    //     if ($id) { 
    //         $id->delete(); 
    //         $this->updateTable();
    //         $this->dispatch('msg-deleted');
    //     }
    // }

}
