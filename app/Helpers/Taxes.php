<?php

namespace App\Helpers;

/**
 * Handles taxes ratios.
 */
class Taxes
{
    /**
     * Check if shop taxes are enabled.
     *
     * @return bool
     */
    public static function areEnabled()
    {
        return config('shop.taxes') == true;
    }

    /**
     * Check if the product prices already contain taxes.
     *
     * @return bool
     */
    public static function productPricesContainTaxes()
    {
        return config('shop.product_price_contains_taxes') == true;
    }

    /**
     * Get the tax ratio (set statically or based on location)
     *
     * @return float
     */
    public static function getTaxRatio()
    {
        return config('shop.tax_ratio') ?: Location::getTaxRatio();
    }
}
