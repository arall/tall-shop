@props(['price'])

@php
    use App\Helpers\Taxes;
    use App\Helpers\Location;

    if (Taxes::areEnabled()) {
        if (!Taxes::productPricesContainTaxes()) {
            $tax = Taxes::getTaxRatio();
            if ($tax) {
                $price += $price * $tax;
            }
        }
    }
@endphp

@price($price)
