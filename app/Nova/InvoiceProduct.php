<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

class InvoiceProduct extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\InvoiceProduct::class;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make('Name'),

            BelongsTo::make('Product')
                ->sortable()->nullable(),

            Text::make('Variants'),

            Number::make('Units')
                ->sortable(),

            Currency::make('Unit Price')
                ->sortable(),

            Currency::make('Price')
                ->sortable(),
        ];
    }
}
