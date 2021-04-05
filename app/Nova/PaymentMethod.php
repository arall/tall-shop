<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;

class PaymentMethod extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PaymentMethod::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Config';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Boolean::make('Status')
                ->sortable(),

            Text::make('Name')
                ->sortable(),

            Text::make('Type')
                ->sortable(),

            Currency::make('Price')
                ->sortable(),

            Currency::make('Price Percent')
                ->sortable(),
        ];
    }
}
