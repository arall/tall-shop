<?php

namespace App\Helpers;

use App\Models\Product;

class Cart
{
    /**
     * Add a product into the cart.
     *
     * @param integer $productId
     * @param array $variantOptions
     * @return void
     */
    public static function add(int $productId, array $variantOptions = [])
    {
        $cart = self::get();
        $hash = md5($productId . '-' . json_encode($variantOptions));
        $quantity = 1;
        if (isset($cart[$hash])) {
            $quantity = $cart[$hash]['quantity'] + 1;
        }

        $cart[$hash] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'option_ids' => $variantOptions,
        ];

        request()->session()->put('cart', $cart);
    }

    /**
     * Increase the quantity of a product.
     *
     * @param string $hash
     * @return void
     */
    public static function increase(string $hash)
    {
        $cart = self::get();
        if (isset($cart[$hash])) {
            $cart[$hash]['quantity']++;
        }

        request()->session()->put('cart', $cart);
    }

    /**
     * Get the total amount of products in the cart.
     *
     * @return void
     */
    public static function getTotalProducts()
    {
        return count(self::get());
    }

    /**
     * Decrease the quantity of a product.
     *
     * If the quantity is 0, the product will be removed.
     *
     * @param string $hash
     * @return void
     */
    public static function decrease(string $hash)
    {
        $cart = self::get();
        if (isset($cart[$hash])) {
            if ($cart[$hash]['quantity'] == 1) {
                unset($cart[$hash]);
            } else {
                $cart[$hash]['quantity']--;
            }
        }

        request()->session()->put('cart', $cart);
    }

    /**
     * Calculate the total price.
     *
     * @return float
     */
    public static function getTotalPrice()
    {
        $total = 0;
        foreach (self::get() as $item) {
            $product = Product::find($item['product_id']);
            $total += $product->getTotalPrice($item['option_ids'])  * $item['quantity'];
        }

        return $total;
    }

    /**
     * Remove a product.
     *
     * @param string $hash
     * @return void
     */
    public static function remove(string $hash)
    {
        $cart = self::get();
        if (isset($cart[$hash])) {
            unset($cart[$hash]);
        }

        request()->session()->put('cart', $cart);
    }

    /**
     * Empty the cart.
     *
     * @return void
     */
    public static function empty()
    {
        request()->session()->put('cart', []);
    }

    /**
     * Get the current cart.
     *
     * @return array
     */
    public static function get()
    {
        return request()->session()->get('cart') ?: [];
    }
}
