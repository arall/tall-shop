@props(['price'])

@php
    use App\Helpers\Taxes;

    $price = Taxes::calcPriceWithTax($price);
@endphp

@price($price)
