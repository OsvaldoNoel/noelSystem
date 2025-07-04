<?php

namespace App\Livewire\Tenant\Stock\Productos;

use App\Models\Product; 
use Livewire\Component;

class SelectedProduct extends Component
{
    public $componentName, $datos;  

    public function mount()
    {
        $this->componentName = 'Producto'; 
        $this->updateTable();  
    }

    public function updateTable()
    {  
        $this->datos = Product::query() 
            ->select(
                'products.id',
                'products.tenant_id',
                'products.code',
                'products.name',
                'products.stock',
                'products.priceList2',
                'products.image',

                'marcas.name as marca_name',
                'presentations.name as presentation_name',
            ) 
            ->join('marcas', 'products.marca_id', '=', 'marcas.id')
            ->join('presentations', 'products.presentation_id', '=', 'presentations.id')
            ->get()->toJson();
        $this->dispatch($this->componentName, $this->datos);  
    }  
}
