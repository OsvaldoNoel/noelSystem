<?php

namespace App\Livewire\Tenant\Reportes\Compras;

use App\Models\Compra;
use Livewire\Component;

class ReporteComprasController extends Component
{
    public $componentName = 'Compras';
    public $datos;
    public $search = '';
    public $dateRange = '';

    public function mount()
    {
        $this->updateTable();
    }

    public function updateTable()
    {
        $this->datos = Compra::query()
            ->when($this->search, function ($query) {
                $query->where('stock', 'like', 0)
                    ->orWhere('date', 'like', '%' . $this->dateRange . '%')
                    ->orWhere('numero_factura', 'like', '%' . $this->search . '%');
            })
            ->select([
                'compras.id',
                'compras.status',
                'compras.user_id',
                'compras.proveedor_id',
                'compras.date',
                'compras.tipoCompr',
                'compras.condCompr',
                'compras.numero_factura',
                'compras.timbrado',
                'compras.otrosRecibo',
                'compras.total',
                'compras.iva_10',
                'compras.iva_5',
                'compras.exenta',
                'compras.items',

                'users.name as user_name',
                'proveedors.name as proveedor_name',
            ])
            ->join('users', 'compras.user_id', '=', 'users.id')
            ->join('proveedors', 'compras.proveedor_id', '=', 'proveedors.id')
            ->get()
            ->map(function ($item) {
                $item->date_formatted = \Carbon\Carbon::parse($item->date)->format('d-m-Y');    // Formatear fecha
                $item->total_formatted = number_format($item->total, 0, ',', '.');              // Formatear total 
                
                return $item;
            });
    
        $this->dispatch('tableUpdated', datos: $this->datos);
    }

    public function render()
    {
        return view('livewire.tenant.reportes.compras.reporte-compras-controller');
    }
}
