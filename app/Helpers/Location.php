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
     * Set the current user location country and tax ratio.
     *
     * @return void
     */
    public static function setLocation()
    {
        if (self::hasLocation()) {
            return;
        }

        $position = IPLocation::get();
        if (!$position) {
            return;
        }

        $country = $position->countryName;
        session()->put('location.country', $country);

        // Taxes based on user location?
        if (config('shop.taxes') && !config('shop.tax_ratio')) {
            $countries = new Countries();
            try {
                session()->put(
                    'location.tax',
                    $countries->whereNameCommon($country)->first()->hydrateTaxes()->taxes->vat->rates->first()->amounts->last()->amount
                );
            } catch (\Exception $e) {
                // do nothing
            }
        }
    }

    /**
     * Check if the user location is set on session.
     *
     * @return boolean
     */
    public static function hasLocation()
    {
        return session()->exists('location.country');
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
        return session()->get('location.tax') ?: 0;
    }

    /**
     * Get the current user country.
     *
     * @return string|null
     */
    public static function getCountry()
    {
        return session()->get('location.country') ?: null;
    }
}
