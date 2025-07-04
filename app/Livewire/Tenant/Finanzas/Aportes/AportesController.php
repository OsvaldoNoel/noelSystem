<?php

namespace App\Livewire\Tenant\Finanzas\Aportes;

use App\Models\Aportante;
use App\Models\Aporte;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class AportesController extends Component
{

    public $selected_id, $aportante_id, $operacion, $destino = 0, $monto, $detail, $saldo, $date;
    public $componentName, $datos;

    public function mount()
    {
        $this->componentName = 'Aporte'; 
    }

    public function cancel()
    {
        $this->reset('selected_id', 'aportante_id', 'destino', 'monto', 'detail', 'saldo');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function aporte()
    {
        $this->operacion = 0;
        $this->dispatch('showModal');
    }

    public function devolucion()
    {
        $this->operacion = 1;
        $this->dispatch('showModal');
    }

    public function convertInteger()
    {
        $monto = (int)str_replace(".", "", $this->monto);
        $monto != null ? $this->monto = number_format($monto, 0, ",", ".") : $this->monto = null;
    }

    #[On('selectedAportante')]
    public function selectedAportante($id)
    {
        $this->aportante_id = $id;
    }

    #[On('selectedBanco')]
    public function func_Destino($id)
    {
        $this->destino = $id;
        if ($id == 0 || $id == null) {
            $this->dispatch('selectedCaja');
        }
    }

    protected $messages = [
        'aportante_id.required' => 'El nombre del aportante es requerido',
        'monto.required' => 'El monto es requerido',
        'destino.required' => 'Debe seleccionar una entidad',
        'date.required' => 'Debe seleccionar una fecha',
        'date.date' => 'El formato de fecha debe ser dd-mm-yyyy',
        'date.date_format' => 'El formato de fecha no corresponde',
    ];

    public function store()
    {
        $this->validate([
            'aportante_id' => 'required',
            'monto' => 'required',
            'destino' => 'required',
            'date' => 'required|date|date_format:d-m-Y H:i',
        ]);

        try {
            DB::beginTransaction();

            $this->monto = (int)str_replace(".", "", $this->monto);
            $consulta = Aporte::select("saldo")->where('aportante_id', '=', $this->aportante_id)->orderBy("id", "desc")->first();
            //consultar si ya existe un registro par este tenant
            if ($consulta != null) {
                $saldoAnt = $consulta->saldo;
                //consultar si la operacion es de aporte
                if ($this->operacion == 0) {
                    $this->saldo = $saldoAnt + $this->monto;
                } else {
                    $this->saldo = $saldoAnt - $this->monto;
                }
            } else {
                $this->saldo = $this->monto;
            };

            Aporte::create([
                'tenant_id' => Auth::user()->tenant_id,
                'aportante_id' => $this->aportante_id,
                'operacion' => $this->operacion,
                'destino' => $this->destino,
                'date' => $this->date,
                'monto' => $this->monto, 
                'saldo' => $this->saldo,
                'detail' => $this->detail,
            ]);

            $record = Aportante::find($this->aportante_id);
            $record->update([ 
                'aporte' => $this-> saldo,
            ]); 

            DB::commit();
            $this->dispatch('closeModal');
            $this->dispatch('msg-added');
            $this->dispatch('bancos_id', null);
            $this->dispatch('aportante_id', null);
            $this->dispatch('updateSaldoAportante', null);
        } catch (\Throwable $th) {

            DB::rollBack();

            //solo para desarrollo
            dd($th->getMessage());
        }
    }

    // public function edit($id)
    // {    
    //     $record = Aporte::findOrFail($id);

    //     $this->selected_id = $id; 
    // 	$this->name = $record-> name;
    //     $this->ubi = $record-> ubi;  

    //     $this->dispatch('showEditModal');
    // }

    // public function update()
    // {
    //     $this->validate([
    //         'name' => [
    //             'min:3',
    //             'max:50',
    //             'required',
    //             Rule::unique('aportes')->ignore($this->selected_id)->where(function ($query) {
    //                 $query->where('tenant_id', Auth::user()->tenant_id);
    //             })
    //         ],
    //         'ubi' => [
    //             'min:3',
    //             'max:50',
    //             'required',
    //             Rule::unique('aportes')->ignore($this->selected_id)->where(function ($query) {
    //                 $query->where('tenant_id', Auth::user()->tenant_id);
    //             })
    //         ], 
    //     ]);

    //     if ($this->selected_id) {
    //         $record = Aporte::find($this->selected_id);
    //         $record->update([
    //             'name' => $this->name,
    //             'ubi' => $this->ubi, 
    //         ]);

    //         $this->dispatch('closeModal');
    //         $this->dispatch('msg-updated');
    //         $this->updateData(); 
    //     }
    // } 

    // public function destroy(Aporte $id)
    // {
    //     if ($id) {
    //         $id->delete();
    //         $this->updateData();
    //         $this->dispatch('msg-deleted');
    //     }
    // }
}
