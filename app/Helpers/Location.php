<?php

namespace App\Helpers;

use Stevebauman\Location\Facades\Location as IPLocation;
use PragmaRX\Countries\Package\Countries;

/**
 * Handles user location information.
 */
class Location
{
    /**
     * Load the current user location country and tax ratio.
     *
     * @return void
     */
    public static function loadLocation()
    {
        if (session()->has('location.country')) {
            return;
        }

        $position = IPLocation::get();
        if (!$position) {
            session()->put('location.country', 'none');
            return;
        }

        $country = $position->countryCode;
        session()->put('location.country', $country);

        self::setTaxByCountry($country);
    }

    /**
     * Set the tax ratio by country code.
     *
     * @param  string $country Country code
     * @return void
     */
    private static function setTaxByCountry(string $country)
    {
        // Taxes based on user location?
        if (!config('shop.taxes') || config('shop.tax_ratio')) {
            return;
        }

        $countries = new Countries();
        try {
            $tax = $countries->where('cca2', $country)->first()->hydrateTaxes()->taxes->vat->rates->first()->amounts->last()->amount;
            session()->put('location.tax', $tax);
        } catch (\Exception $e) {
            // do nothing
        }
    }

    /**
     * Force the location of an user.
     *
     * @param  string $country Country code
     * @return void
     */
    public static function setLocation(string $country)
    {
        session()->put('location.country', $country);
        self::setTaxByCountry($country);
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
        self::loadLocation();

        return session()->get('location.tax') ?: 0;
    }

    /**
     * Get the current user country code.
     *
     * @return string|null
     */
    public static function getCountry()
    {
        self::loadLocation();

        return session()->get('location.country') ?: null;
    }
}
