<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;


trait Cart
{

    protected function cartKey()
    {
        return 'cart_' . session()->getId();
    }

    function addOrUpdate($product, $marca, $presentation)
    {
        try {
            $cartKey = $this->cartKey();

            //optenemos todos los productos acutales del carrito
            $cart = session()->get($cartKey, []);

            $qty = 1;
            $price = $product->priceList2;
            $total = round($price * $qty);

            //update
            if (isset($cart[$product->id])) {
                $cart[$product->id]['qty'] += $qty;
                $cart[$product->id]['total'] += $total;

                $this->dispatch(
                    'updateInput',
                    qty: $cart[$product->id]['qty'],
                    id: $cart[$product->id]['id']
                );

            } else { 
                $cart[$product->id] = [
                    'id' => $product->id,
                    'code' => $product->code,
                    'image' => $product->image,
                    'name' => $product->name,
                    'marca' => $marca,
                    'presentation' => $presentation,
                    'price' => $product->priceList2,
                    'qty' => $qty,
                    'total' => $total,
                ];
            }

            //guardamos la sesion
            session()->put($cartKey, $cart);
            return true;
        } catch (\Throwable $th) {
            Log::info("AddOrUpdate " . $th->getMessage());
            return false;
        }
    }

    function replaceItemPrice($product_id, $currentPrice)
    {
        try {
            $cartKey = $this->cartKey();

            //optenemos todos los productos acutales del carrito
            $cart = session()->get($cartKey, []);

            if (isset($cart[$product_id])) {

                $price = $currentPrice;
                $total = round($price * $cart[$product_id]['qty']);

                $cart[$product_id]['price'] = $price;
                $cart[$product_id]['total'] = $total; 

                //guardamos la sesion
                session()->put($cartKey, $cart);
                return true;
            }
        } catch (\Throwable $th) {
            Log::info("replaceItemPrice " . $th->getMessage());
            return false;
        }
    }

    function replaceQty($product_id, $qty = 1)
    {
        try {
            $cartKey = $this->cartKey();

            //optenemos todos los productos acutales del carrito
            $cart = session()->get($cartKey, []);

            if (isset($cart[$product_id])) {

                $price = $cart[$product_id]['price'];
                $total = round($price * $qty);

                $cart[$product_id]['qty'] = $qty;
                $cart[$product_id]['total'] = $total;

                $this->dispatch(
                    'updateInput',
                    qty: $cart[$product_id]['qty'],
                    id: $cart[$product_id]['id']
                );

                //guardamos la sesion
                session()->put($cartKey, $cart);
                return true;
            }
        } catch (\Throwable $th) {
            Log::info("replaceQty " . $th->getMessage());
            return false;
        }
    }

    function removeItemCart($product_id)
    {
        $cartKey = $this->cartKey();

        //optenemos todos los productos acutales del carrito
        $cart = session()->get($cartKey, []);

        if (isset($cart[$product_id])) {
            unset($cart[$product_id]);

            //guardamos la sesion
            session()->put($cartKey, $cart);
            return true;
        }
    }

    function getCartItems()
    {
        $cartKey = $this->cartKey();
        return session()->get($cartKey, []);
    }

    function getTotal()
    {
        try {
            $cartKey = $this->cartKey();
            $cart = session()->get($cartKey, []);

            $total = 0;

            //iterar los productos del carrito
            foreach ($cart as $product) {
                $total += $product['total'];
            }
            return round(intval($total));
        } catch (\Throwable $th) {
            return 0;
        }
    }

    function totalItems()
    {
        $cartKey = $this->cartKey();
        $cart = session()->get($cartKey, []);

        $items = 0;

        foreach ($cart as $product) {
            $items++;
        }
        return $items;
    }

    function clearCart()
    {
        $cartKey = $this->cartKey();
        session()->forget($cartKey);
    }
}
