<?php

namespace App\Helpers;

use Stevebauman\Location\Facades\Location as IPLocation;
use PragmaRX\Countries\Package\Countries;

/**
 * Handles user location information.
 *
 * @todo SetLocation was called in a middleware,
 * but doesn't seem to work in all environments,
 * so I'm calling it in getTaxRatio and getCountry.
 */
class Location
{
    /**
     * Set the current user location country and tax ratio.
     *
     * @return void
     */
    public static function setLocation()
    {
        $position = IPLocation::get();
        if (!$position) {
            return;
        }

        $country = $position->countryCode;
        session()->put('location.country', $country);

        // Taxes based on user location?
        if (config('shop.taxes') && !config('shop.tax_ratio')) {
            $countries = new Countries();
            try {
                $tax = $countries->where('cca2', $country)->first()->hydrateTaxes()->taxes->vat->rates->first()->amounts->last()->amount;
                session()->put('location.tax', $tax);
            } catch (\Exception $e) {
                // do nothing
            }
        }
    }

    /**
     * Get the current user tax ratio based on country.
     *
     * Format in decimal: 0.21
     *
     * @return float
     */
    public static function getTaxRatio()
    {
        self::setLocation();

        return session()->get('location.tax') ?: 0;
    }

    /**
     * Get the current user country.
     *
     * @return string|null
     */
    public static function getCountry()
    {
        self::setLocation();

        return session()->get('location.country') ?: null;
    }
}
