<?php

namespace App\Helpers;

use App\Models\Product;

/**
 * Handles user shopping cart.
 */
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

        session()->put('cart', $cart);
    }

    /**
     * Change the quantity of a product.
     *
     * @param string $hash
     * @param int $quantity
     * @return void
     */
    public static function changeQuantity(string $hash, int $quantity)
    {
        $cart = self::get();
        if (isset($cart[$hash])) {
            $cart[$hash]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);
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
     * Calculate the total price.
     *
     * @return float
     */
    public static function getTotalPrice()
    {
        $total = 0;
        foreach (self::get() as $item) {
            $product = Product::find($item['product_id']);
            $price = $product->getPrice($item['option_ids']) * $item['quantity'];
            $price = Taxes::calcPriceWithTax($price);

            $total += $price;
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

        session()->put('cart', $cart);
    }

    /**
     * Empty the cart.
     *
     * @return void
     */
    public static function empty()
    {
        session()->put('cart', []);
    }

    /**
     * Get the current cart.
     *
     * @return array
     */
    public static function get()
    {
        return session()->get('cart') ?: [];
    }
}
