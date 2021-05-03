# TALL Shop
An e-commerce website using the TALL stack.

It uses [Laravel Nova](https://nova.laravel.com) for the Admin panel (which requires a license).

Initial preset used: https://github.com/laravel-frontend-presets/tall

## Taxes

Taxes can be enabled or dissabled with .env `SHOP_TAX` parameter (true or false).
If enabled, you can indicate if the products prices already contain taxes or not with `SHOP_PRODUCT_CONTAINS_TAXES`.

The tax ratio can be set statically in `SHOP_TAX_RATIO`, for example 0.21 for the 21%. Or can be set to false to be calculated based on user location (by using [pragmarx/countries](https://github.com/antonioribeiro/countries) and [stevebauman/location](https://github.com/stevebauman/location)).

And lastly, the `tax_default_ratio` will be the default tax ratio, in case user location cannot be obtained.

Keep in mind that taxes are applied globaly on all products.

# Todo / Pending
* Calculate shipping price based on products weight.
* Calculate payment method price based on total price.
* Generate invoices as PDF.
* Testing:
* - Payment Methods
* - Invoices 

# Resources

* https://tailwindcomponents.com/component/e-commerce-product-page
* https://tailwindui.com
* https://laravel-livewire.com/screencasts
* https://nova.laravel.com
