<?php

namespace App\Livewire\Tenant\Reportes\Compras;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\CompraItem;

class DetalleCompras extends Component
{
    // Variables públicas para la vista
    public $header = [];
    public $items = [];
    public $tipoDocumento = '';
    public $condicionPago = '';
    public $numeroDocumento = '';
    public $tieneIva10 = false;
    public $tieneIva5 = false;
    public $tieneExenta = false;
    public $tieneDosVariables = false;
    public $totalDescuento = 0;

    #[On('showCompraDetalle')]
    public function showCompraDetalle($data)
    {
        $this->header = $data;

        // Configurar propiedades basadas en los datos
        $this->tipoDocumento = ($data['tipoCompra'] ?? null) == 1 ? 'Factura LEGAL' : 'RECIBO COMUN';
        $this->condicionPago = ($data['tipoCompra'] ?? null) == 1 ? ($data['condCompra'] == 1 ? '---- Contado' : '---- Crédito') : '';
        $this->numeroDocumento = ($data['tipoCompra'] ?? null) == 1 ? ($data['nroFac'] ?? '') : ($data['nroRec'] ?? '');
        $this->tieneIva10 = floatval($data['iva10x'] ?? 0);
        $this->tieneIva5 = floatval($data['iva5x'] ?? 0);
        $this->tieneExenta = floatval($data['exc'] ?? 0);

        $this->tieneDosVariables = false;
        if (
            $this->tieneIva10 && $this->tieneIva5 ||
            $this->tieneIva10 && $this->tieneExenta ||
            $this->tieneIva5 && $this->tieneExenta
        ) {
            $this->tieneDosVariables = true;
        };

        // Obtener los items
        $this->items = CompraItem::with([
                'product' => function ($query) {
                    $query->select('id', 'name', 'marca_id', 'presentation_id')
                        ->with(['marca:id,name', 'presentation:id,name']);
                }
            ])
            ->where('compra_id', $data['id'])
            ->select([
                'id',
                'product_id',
                'quantity',
                'price_unit',
                'price_unit_discounted',
                'discount_amount',
                'discount_percent',
                'row_total',
                'iva'
            ])
            ->get();

        // Calcular el total de descuentos 
        $this->totalDescuento = 0;
        foreach ($this->items as $item) { 
            $this->totalDescuento += floatval($item['discount_amount'] ?? 0); // Evita "Undefined index" 
        }

        $this->dispatch('showModal', 'ComprasDetalle');
    }
}
