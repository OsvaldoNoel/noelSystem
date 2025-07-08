<?php

namespace App\Livewire\Tenant\Compras;

use App\Models\Compra;
use App\Models\Marca;
use App\Models\Presentation;
use App\Models\Product;
use App\Traits\CartPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Livewire\Component;

class ComprasController extends Component
{
    use CartPurchase;

    public $cartContent, $itemsCart, $totales, $header;
    public $total_exc, $iva10x, $iva5x, $ivaExc, $gravada_10, $gravada_5;
    public $tipoComp = 1;

    public $numeroFactura;
    public $timbrado;

    // Método de validación personalizado
    protected function rules()
    {
        // Solo aplica reglas si tipoComp == 1
        if ($this->tipoComp == 1) {
            $rules['numeroFactura'] = 'required|string|max:15';
            $rules['timbrado'] = 'required|regex:/^\d{8}$/';
        } else {
            // Si tipoComp != 1, las campos son opcionales
            $rules['numeroFactura'] = 'nullable';
            $rules['timbrado'] = 'nullable';
        }

        return $rules;
    }

    // Mensajes personalizados
    protected function messages()
    {
        return [
            'numeroFactura.required' => 'El Nro de Fac. es requerido',
            'numeroFactura.max' => 'Máximo 15 caracteres',
            'timbrado.required' => 'El timbrado es obligatorio',
            'timbrado.regex' => 'Debe tener 8 dígitos numéricos',
        ];
    }

    // Validación integrada de Livewire  
    #[Rule(
        'required|date_format:d - m - Y',
        message: [
            'fechaFactura.required' => 'La fecha es obligatoria',
            'fechaFactura.date_format' => 'Use el formato DD - MM - AAAA'
        ]
    )]
    public $fechaFactura;

    #[Rule(
        'nullable|string|max:15',
        message: [
            'numeroOtros.max' => 'Máximo 15 caracteres',
        ]
    )]
    public $numeroOtros;

    #[Rule(
        'required',
        message: [
            'proveedor_id.required' => 'El proveedor es requerido',
        ]
    )]
    public $proveedor_id;

    public function render()
    {
        $this->header = $this->getHeaderData();
        $this->cartContent = $this->getCartItems();
        $this->totales = $this->getTotal();
        $this->itemsCart = $this->totalItems();
        $this->calculoIva();

        return view('livewire.tenant.compras.compras-controller');
    }

    #[On('selectedProveedor')] //proveniente desde el componente selected proveedor
    public function selectedProveedor($id)
    {
        $this->proveedor_id = $id;
        $this->borrarErrores('proveedor_id');
        $this->dispatch('setHeader');
    }

    // Método para sincronizar el header del trait con las propiedades
    protected function syncHeaderData()
    {
        $header = $this->getHeaderData();

        if ($header) {
            $this->tipoComp = $header['tipo_Comprobante'];
            $this->fechaFactura = $header['fecha_Fac'];
            $this->numeroFactura = $header['nro_Fac'];
            $this->timbrado = $header['nro_Tim'];
            $this->numeroOtros = $header['nro_Otros'];

            $this->numeroFactura == "" ? $this->numeroFactura = null : $this->numeroFactura;
            $this->timbrado == "" ? $this->timbrado = null : $this->timbrado;
            $this->numeroOtros == "" ? $this->numeroOtros = null : $this->numeroOtros;
        }
    }

    public function calculoIva()
    {
        $this->iva10x = round(($this->totales['total_10'] / 11), 0);
        $this->iva5x = round(($this->totales['total_5'] / 21), 0);

        $this->gravada_10 = $this->totales['total_10'] - $this->iva10x;
        $this->gravada_5 = $this->totales['total_5'] - $this->iva5x;
    }

    #[On('scanCode')]
    public function scanCode($barcode)
    {
        $selected = Product::where('code', $barcode)->first();

        if (!$selected) {
            $this->dispatch('ErrorDismiss', 'El código no está registrado');
            return;
        }

        $marca = Marca::find($selected->marca_id);
        $presentation = Presentation::find($selected->presentation_id);

        $this->dispatch(
            'selectedProduct',
            product: $selected->id,
            marca: $marca->name,
            presentation: $presentation->name
        );
    }

    #[On('selectedProduct')]
    public function addItem(Product $product, $marca, $presentation)
    {
        $this->addOrUpdate($product, $marca, $presentation);
    }

    #[On('replaceItemQty')]
    public function replaceItemQty($product_id, $currentqty, $qty)
    {
        if (!is_numeric($qty)) {
            $this->dispatch(
                'invalidQty',
                id: $product_id,
                qty: number_format($currentqty, 0, '', '') // Enviar sin decimales al front
            );
            return;
        }

        if ($qty == 0) {
            $this->removeItemCart($product_id);
            return;
        }
        $this->replaceQty($product_id, (float)$qty); // Aseguramos tipo float
    }

    #[On('replaceItemPrice')]
    function replaceItemPrice($product_id, $currentprice, $price)
    {
        if (!is_numeric($price)) {
            $this->dispatch('invalidPrice', id: $product_id, price: $currentprice);
            return;
        }
        $this->replaceRowPrice($product_id, $price);
    }

    #[On('replaceItemDescGs')]
    function replaceItemDescGs($product_id, $currentDesc, $descGs)
    {
        if (!is_numeric($descGs)) {
            $this->dispatch('invalidDesc', id: $product_id, desc: $currentDesc);
            return;
        }
        $this->replaceRowDescGs($product_id, $descGs);
    }

    #[On('replaceItemDescX')]
    function replaceItemDescX($product_id, $currentDescX, $descX)
    {
        if (!is_numeric($descX)) {
            $this->dispatch('invalidDescX', id: $product_id, descX: $currentDescX);
            return;
        }
        $this->replaceRowDescX($product_id, $descX);
    }

    #[On('replaceItemIva')]
    function replaceItemIva($product_id, $iva)
    {
        $this->replaceRowIva($product_id, $iva);
    }

    #[On('replacePriceSubtotal')]
    function replacePriceSubtotal($product_id, $currentPrice)
    {
        $this->replaceItemSubtotal($product_id, $currentPrice);
    }

    function deleteItem($product_id)
    {
        $this->removeItemCart($product_id);
    }

    public function store()
    {
        $header = $this->getHeaderData();
        $totales = $this->getTotal();
        $this->syncHeaderData();
        $this->validate();

        // Validación adicional
        if ($this->itemsCart <= 0) {
            $this->addError('store', 'Debe agregar al menos un producto');
            return;
        }

        // Validar que ningún item tenga subTotal cero
        foreach ($this->getCartItems() as $item) {
            if ($item['subTotal'] <= 0) {
                $producto = Product::find($item['id']);
                $this->addError('store', "El producto {$producto->name} tiene un Sub-total inválido (cero)");
                return;
            }
        }

        try {
            DB::beginTransaction();
 
            // 1. Crear la compra con Eloquent
            $compra = new Compra([
                'tenant_id' => Auth::user()->tenant_id,
                'user_id' => Auth::user()->id,
                'proveedor_id' => $this->proveedor_id,
                'date' => Carbon::createFromFormat('d - m - Y', $header['fecha_Fac'])->format('Y-m-d'),
                'numero_factura' => $this->numeroFactura,
                'timbrado' => $this->timbrado,
                'otrosRecibo' => $this->numeroOtros,
                'condCompr' => $header['cond_Compra'],
                'tipoCompr' => $header['tipo_Comprobante'],
                'items' => $this->itemsCart,
                'total' => $totales['total'],
                'exenta' => $totales['total_exc'],
                'gravada_10' => $this->gravada_10,
                'gravada_5' => $this->gravada_5,
                'iva_10' => $this->iva10x,
                'iva_5' => $this->iva5x,
            ]);
            $compra->save();

            // 2. Crear items usando la relación
            foreach ($this->getCartItems() as $item) {
                $compra->items()->create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'compra_id' => $compra->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price_unit' => $item['price'],
                    'price_unit_discounted' => $item['precioConDesc'],
                    'discount_amount' => $item['descGs'],
                    'discount_percent' => $item['descX'],
                    'row_total' => $item['subTotal'],
                    'iva' => $item['iva'],
                ]);

                // 3. Actualizar stock (con Eloquent)
                Product::find($item['id'])->increment('stock', $item['qty']);
                // También el costo si se debe actualiar aqui
            }

            DB::commit();
            $this->dispatch('registroExitoso', text: 'Compra Registrada...', bg: 'success'); 
            $this->clearCart();
            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = $e->getMessage();

            dd('Error en compra: ' . $errorMessage);
            $this->dispatch('errorEnCompra', message: 'Ocurrió un error al procesar la compra');
        }
    } 

    public function borrarErrores($data)
    {
        $this->resetErrorBag($data);
    }
}


