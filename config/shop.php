<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Taxes
    |--------------------------------------------------------------------------
    |
    | Enable or disable taxes.
    |
    */

    'taxes' => env('SHOP_TAX', true),

    /*
    |--------------------------------------------------------------------------
    | Product Taxes
    |--------------------------------------------------------------------------
    |
    | If product prices contain Taxes, set this to true.
    | If the taxes should be added in top of the product price, set this to false.
    |
    */

    'product_price_contains_taxes' => env('SHOP_PRODUCT_CONTAINS_TAXES', true),

    /*
    |--------------------------------------------------------------------------
    | Taxes ratios
    |--------------------------------------------------------------------------
    |
    | Set to false to calculate the tax ratio based on the costumer location.
    | Set to an integer value to use that ratio to all costumers.
    |
    */

    'tax_ratio' => env('SHOP_TAX_RATIO', false),

    /*
    |--------------------------------------------------------------------------
    | Default taxes ratios
    |--------------------------------------------------------------------------
    |
    | If set, when tax ratio cannot be obtained from user location,
    | will set this property vaue as default value.
    |
    */

    'tax_default_ratio' => env('SHOP_DEFAULT_TAX_RATIO', false),
];
