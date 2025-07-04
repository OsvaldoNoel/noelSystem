<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;


trait CartPurchase
{
    protected function cartKey()
    {
        return 'cart_purchase_' . session()->getId();
    }

    // ==================== HEADER METHODS ====================

    public function setHeaderData($fecha, $cond, $tipo, $nroFac, $nroTim, $nroOtros, $proveedorId)
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            $cart['header'] = [
                'fecha_Fac' => $fecha,
                'cond_Compra' => $cond,
                'tipo_Comprobante' => $tipo,
                'nro_Fac' => $nroFac,
                'nro_Tim' => $nroTim,
                'nro_Otros' => $nroOtros,
                'proveedor_id' => $proveedorId,
            ];

            session()->put($cartKey, $cart);
            return true;
        } catch (\Throwable $th) {
            Log::error("setHeaderData: " . $th->getMessage());
            return false;
        }
    }

    public function getHeaderData()
    {
        $cartKey = $this->cartKey();
        $cart = session()->get($cartKey, []);

        return $cart['header'] ?? null;
    }

    // ==================== PRODUCT METHODS ====================

    public function getProducts()
    {
        $cartKey = $this->cartKey();
        $cart = session()->get($cartKey, []);

        if (isset($cart['header'])) {
            unset($cart['header']);
        }

        return $cart;
    }

    function addOrUpdate($product, $marca, $presentation)
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            // Separar header y productos
            $header = $cart['header'] ?? null;
            $products = $cart;
            if (isset($cart['header'])) {
                unset($products['header']);
            }

            $qty = 1;

            // Update producto existente
            if (isset($products[$product->id])) {
                $subTotal = round($products[$product->id]['price'] * $qty);

                $products[$product->id]['qty'] += $qty;
                $products[$product->id]['subTotal'] += $subTotal;

                $this->dispatch(
                    'updateInputQty',
                    qty: $products[$product->id]['qty'],
                    id: $products[$product->id]['id']
                );
            } else {
                // Nuevo producto
                $products[$product->id] = [
                    'id' => $product->id,
                    'code' => $product->code,
                    'image' => $product->image,
                    'name' => $product->name,
                    'marca' => $marca,
                    'presentation' => $presentation,
                    'qty' => $qty,
                    'price' => 0,
                    'descGs' => 0,
                    'descX' => 0.00,
                    'precioConDesc' => 0,
                    'subTotal' => 0,
                    'iva' => 1, // Valor por defecto (10%)
                ];
            }

            // Reconstruir el carrito
            $newCart = $products;
            if ($header) {
                $newCart = ['header' => $header] + $products;
            }

            session()->put($cartKey, $newCart);
            return true;
        } catch (\Throwable $th) {
            Log::error("AddOrUpdate: " . $th->getMessage());
            return false;
        }
    }

    function replaceItemSubtotal($product_id, $currentPrice)
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            if (isset($cart[$product_id])) {
                $price = $currentPrice;
                $subTotal = round($price * $cart[$product_id]['qty']);

                $cart[$product_id]['price'] = $price;
                $cart[$product_id]['subTotal'] = $subTotal;

                session()->put($cartKey, $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("replaceItemPrice: " . $th->getMessage());
            return false;
        }
    }

    function replaceQty($product_id, $qty)
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            if (isset($cart[$product_id])) {
                $precioConDesc = $cart[$product_id]['precioConDesc'];
                $subTotal = round($precioConDesc * $qty);

                $cart[$product_id]['qty'] = $qty;
                $cart[$product_id]['subTotal'] = $subTotal;

                $this->dispatch(
                    'updateInputQty',
                    qty: $cart[$product_id]['qty'],
                    id: $cart[$product_id]['id']
                );

                session()->put($cartKey, $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("replaceQty: " . $th->getMessage());
            return false;
        }
    }

    private function updateCartItemCalculations(&$item, string $field): void
    { 
        if ($field == 'descX') {
            $item['descGs'] = round($item['price'] * ($item['descX'] / 100));
            $this->dispatch('updateInputDescGs', descGs: $item['descGs'], id: $item['id']);
        }else {
            $item['descX'] = number_format(round(($item['descGs'] / $item['price']) * 100, 2), 2);
            $this->dispatch('updateInputDescX', descX: $item['descX'], id: $item['id']);
        }

        // Calcular precios y totales
        $item['precioConDesc'] = $item['price'] - $item['descGs'];
        $item['subTotal'] = round($item['precioConDesc'] * $item['qty']);  
    }

    function replaceRowPrice($product_id, $price)
    {
        try {
            $cart = session()->get($this->cartKey(), []);

            if (isset($cart[$product_id])) {
                $cart[$product_id]['price'] = $price;
                $this->updateCartItemCalculations($cart[$product_id], 'price');
                session()->put($this->cartKey(), $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("replaceRowPrice: " . $th->getMessage());
            return false;
        }
    }

    function replaceRowDescGs($product_id, $descGs)
    {
        try {
            $cart = session()->get($this->cartKey(), []);

            if (isset($cart[$product_id])) {
                $cart[$product_id]['descGs'] = $descGs;
                $this->updateCartItemCalculations($cart[$product_id], 'descGs');
                session()->put($this->cartKey(), $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("replaceRowDescGs: " . $th->getMessage());
            return false;
        }
    }

    function replaceRowDescX($product_id, $descX)
    {
        try {
            $cart = session()->get($this->cartKey(), []);

            if (isset($cart[$product_id])) {
                $cart[$product_id]['descX'] = $descX;
                $this->updateCartItemCalculations($cart[$product_id], 'descX');
                session()->put($this->cartKey(), $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("replaceRowDescX: " . $th->getMessage());
            return false;
        }
    }

    function replaceRowIva($product_id, $iva)
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            if (isset($cart[$product_id])) {
                $cart[$product_id]['iva'] = $iva;
                session()->put($cartKey, $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("replaceInputIva: " . $th->getMessage());
            return false;
        }
    }

    function removeItemCart($product_id)
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            if (isset($cart[$product_id])) {
                unset($cart[$product_id]);
                session()->put($cartKey, $cart);
                return true;
            }
            return false;
        } catch (\Throwable $th) {
            Log::error("removeItemCart: " . $th->getMessage());
            return false;
        }
    }

    function getCartItems()
    {
        $cartKey = $this->cartKey();
        return $this->getProducts(); // Devuelve solo los productos, sin el header
    }

    function getTotal()
    {
        try {
            $products = $this->getProducts();

            $total = 0;
            $total_10 = 0;
            $total_5 = 0;
            $total_exc = 0;

            foreach ($products as $product) {
                $subTotal = $product['subTotal'];
                $total += $subTotal;

                switch($product['iva']) {
                    case 1: $total_10 += $subTotal; break;  // 10%
                    case 2: $total_5 += $subTotal; break;   // 5%
                    default: $total_exc += $subTotal;       // Exenta
                }
            }

            return [
                'total' => round($total, 0),
                'total_10' => round($total_10, 0),
                'total_5' => round($total_5, 0),
                'total_exc' => round($total_exc, 0),
            ];
        } catch (\Throwable $th) {
            Log::error("getTotal: " . $th->getMessage());
            return [
                'total' => 0,
                'total_10' => 0,
                'total_5' => 0,
                'total_exc' => 0,
            ];
        }
    }

    function totalItems()
    {
        return count($this->getProducts());
    }

    function clearCart()
    {
        $cartKey = $this->cartKey();
        session()->forget($cartKey);
        $this->dispatch('updateInputs');
    }

    // Función para obtener toda la información del carrito (header + productos)
    public function getFullCart()
    {
        $cartKey = $this->cartKey();
        return session()->get($cartKey, []);
    }
}
