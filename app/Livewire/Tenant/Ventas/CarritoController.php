<?php

namespace App\Livewire\Tenant\Ventas;

use App\Models\Marca;
use App\Models\Presentation;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Traits\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class CarritoController extends Component
{
    use Cart;

    public $products = [];
    public $cliente_id, $cartContent, $totalCart, $itemsCart, $ivaCart;

    public function render()
    {
        $this->cartContent = $this->getCartItems();
        $this->totalCart = $this->getTotal();
        $this->itemsCart = $this->totalItems();
        
        return view('livewire.tenant.ventas.carrito-controller');
    }

    #[On('selectedCliente')]
    public function selectedCliente($id)
    {
        $this->cliente_id = $id;
    }

    #[On('scanCode')]
    public function scanCode($barcode)
    {
        $selected = Product::where('code', $barcode)->first();

        if ($selected == null) {
            $this->dispatch('ErrorDismiss', 'El codigo no está registrado');
        } else {
            // if($selected->stock < 1){
            //     $this->emit('ErrorDismiss', 'Stock insuficiente');
            //     return;

            $marca = Marca::find($selected->marca_id);
            $presentation = Presentation::find($selected->presentation_id);

            $this->dispatch(
                'selectedProduct',
                product: $selected->id,
                marca: $marca->name,
                presentation: $presentation->name
            );
        }
    }

    #[On('selectedProduct')]
    function addItem(Product  $product, $marca, $presentation)
    {
        if ($this->AddOrUpdate($product, $marca, $presentation)) {
            $this->dispatch('toast', msg: 'Carrito actualizado');
        } else {
            $this->dispatch('toast', msg: 'No se pudo agregar el producto al carrito');
        }
    }
 

    #[On('replaceItemQty')]
    function replaceItemQty($product_id, $currentqty, $qty)
    {
        //dd($qty);
        if (!is_numeric($qty)) {
            //$this->dispatch('toast', msg: 'Cantidad inválida');
            $this->dispatch('invalidqty', id: $product_id, qty: $currentqty);
            return;
        }

        if ($qty == 0) {
            //$this->dispatch('toast', msg: 'Cantidad inválida');
            $this->removeItemCart($product_id);
            return;
        }

        if ($this->replaceQty($product_id, $qty))
            $this->dispatch('toast', msg: 'Carrito actualizado');
        else
            $this->dispatch('toast', msg: 'No se actualizó el carrito');
    }

    #[On('replacePrice')]
    function replacePrice($product_id, $currentPrice)
    { 
        $this->replaceItemPrice($product_id, $currentPrice);
    }

    function deleteItem($product_id)
    {
        $this->removeItemCart($product_id);
    }

    function Store()
    {

        if (intval($this->totalCart) <= 0) {
            $this->dispatch('toast', msg: 'Agrega productos al carrito');
            return;
        }

        try {

            DB::beginTransaction();

            $sale = Sale::create([
                'total' => floatval($this->totalCart),
                'tenant_id' => Auth::user()->tenant_id,
                'items' => intval($this->itemsCart)
            ]);

            foreach ($this->getCartItems() as $product) {

                $articulo = Product::find($product['id']); 
                $oldPrice = $product['price'];
                $currentPrice = $articulo->priceList2; 
                $name = $product['name'] . ' ' . $product['marca'] . ' - ' . $product['presentation'];  

                if($oldPrice == $currentPrice){
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product['id'],
                        'quantity' => $product['qty'],
                        'price' => $product['price'],
                        'total' => $product['total']
                    ]);
                }else{
                    $this->dispatch('precioActualizado', name: $name, product_id: $product['id'], currentPrice: $currentPrice);
                    return;
                }
            }

            DB::commit(); 
            $this->dispatch('registroExitoso', text: 'Venta Registrada...', bg: 'success');
            $this->clearCart();
            $this->reset();

            //
        } catch (\Throwable $th) {

            DB::rollBack();

            //solo para desarrollo
            dd($th->getMessage());
        }
    }

    public function hideResults()
    {
        $this->products = [];
    }
}
