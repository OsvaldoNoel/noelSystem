<?php

namespace App\Livewire\Landlord\Tenants;

use App\Models\Tenant;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Rule; // Este es para propiedades 
use Livewire\Attributes\On;
use Livewire\Component;

class TenantController extends Component
{
    public $selected_id, $name, $sucursal, $status;
    public $componentName = 'Tenant';
    public $search = '';
    public $datos;
    public $is_branch = false; // Para controlar si es sucursal o no
    public $tenantsList = []; // Lista de Tenants para el select 

    public function mount()
    {
        $this->updateTable();
    }

    public function updatedSearch()
    { 
        $this->updateTable();
    }

    #[On('updateTable')]
    public function updateTable()
    {
        // Primero obtenemos los mainTenants que coinciden con la búsqueda
        $mainTenants = Tenant::query()
            ->whereNull('sucursal')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'sucursal', 'status']);

        // Luego obtenemos TODAS sus sucursales (sin filtro de búsqueda)
        $branches = Tenant::query()
            ->whereNotNull('sucursal')
            ->whereIn('sucursal', $mainTenants->pluck('id'))
            ->orderBy('name')
            ->get(['id', 'name', 'sucursal', 'status']);

        // Construimos la estructura ordenada
        $orderedData = [];
        foreach ($mainTenants as $position => $main) {
            $orderedData[] = [
                ...$main->toArray(),
                'sort_order' => ($position + 1) * 1000,
                'is_main' => true
            ];

            // Agregamos todas sus sucursales
            $branches->where('sucursal', $main->id)
                ->each(function ($branch, $index) use (&$orderedData, $position) {
                    $orderedData[] = [
                        ...$branch->toArray(),
                        'sort_order' => (($position + 1) * 1000) + ($index + 1),
                        'is_main' => false
                    ];
                });
        }

        $this->datos = $orderedData;
        $this->dispatch('tableUpdated', datos: $this->datos);
    }

    public function cancel()
    {
        $this->reset('selected_id', 'name', 'sucursal', 'is_branch', 'tenantsList');
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store()
    {
        // Validación condicional para sucursal
        $this->validate([
            'name' => [
                'required',
                'min:3',
                'max:50',
                ValidationRule::unique('tenants')
            ],
            'sucursal' => [
                ValidationRule::requiredIf($this->is_branch),
                'nullable',
                'exists:tenants,id'
            ]
        ], [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'Mínimo 3 caracteres',
            'name.max' => 'Máximo 50 caracteres',
            'name.unique' => 'El nombre ya existe',
            'sucursal.required' => 'Debe seleccionar una Empresa Principal',
            'sucursal.exists' => 'Empresa no válida'
        ]);

        Tenant::create([
            'name' => $this->name,
            'sucursal' => $this->is_branch ? $this->sucursal : null,
            'status' => 1
        ]);

        $this->cancel();
        $this->dispatch('closeModal');
        $this->dispatch('registroExitoso', text: 'Empresa Registrada...', bg: 'success');
        $this->updateTable();
    }

    public function update()
    {
        // Validación manual para update que incluye ignore
        $this->validate([
            'name' => [
                'required',
                'min:3',
                'max:50',
                ValidationRule::unique('tenants')->ignore($this->selected_id)
            ]
        ], [
            'name.required' => 'El nombre de la Tenant es requerido',
            'name.min' => 'El nombre debe tener al menos tres caracteres',
            'name.max' => 'El nombre debe tener como máximo 50 caracteres',
            'name.unique' => 'Una tenant con ese nombre ya fue registrada'
        ]);

        Tenant::find($this->selected_id)->update([
            'name' => $this->name
        ]);

        $this->cancel();
        $this->dispatch('closeModal');
        $this->dispatch('registroExitoso', text: 'Editado exitosamente...', bg: 'success');
        $this->updateTable();
    }

    #[On('statusTenant')]
    public function status($id)
    {
        $record = Tenant::findOrFail($id);
        $record->update(['status' => !$record->status]);
    }

    #[On('deleteTenant')]
    public function destroy(Tenant $id)
    {
        $id->delete();
        $this->updateTable();
        $this->dispatch('msg-deleted');
    }
}
