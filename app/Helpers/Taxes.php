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
        if (config('shop.tax_ratio')) {
            return config('shop.tax_ratio');
        } elseif (Location::getTaxRatio()) {
            return Location::getTaxRatio();
        } elseif (config('shop.tax_default_ratio')) {
            return config('shop.tax_default_ratio');
        }

        return 0;
    }

    /**
     * Adds (if necessary) the tax to a price.
     *
     * @param float $price
     * @param float $taxRatio
     * @return float
     */
    public static function calcPriceWithTax($price, $taxRatio = null)
    {
        if (self::areEnabled() && !self::productPricesContainTaxes()) {
            $taxRatio = $taxRatio ?: self::getTaxRatio();
            return $price + ($price * $taxRatio);
        }

        return $price;
    }

    /**
     * Removes (if necessary) the tax from a price.
     *
     * @param float $price
     * @param float $taxRatio
     * @return float
     */
    public static function calcPriceWithoutTax($price, $taxRatio = null)
    {
        if (self::areEnabled() && self::productPricesContainTaxes()) {
            return self::calcTaxPrice($price, $taxRatio);
        }

        return $price;
    }

    /**
     * Gets the tax total from a taxed price.
     *
     * @param float $price
     * @param float $taxRatio
     * @return float
     */
    public static function calcTaxPrice($price, $taxRatio = null)
    {
        if (self::areEnabled()) {
            $taxRatio = $taxRatio ?: self::getTaxRatio();
            return ($price * ($taxRatio / (1 + $taxRatio)));
        }

        return 0;
    }
}
