<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;

class Invoice extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Invoice::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Invoices';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

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

            Badge::make('Status', function () {
                return $this->getStatus();
            })->map([
                'Draft' => 'info',
                'Submitted' => 'success',
            ]),

            Select::make('Status')->options(\App\Models\Invoice::STATUSES)->onlyOnForms(),

            BelongsTo::make('User')
                ->sortable(),

            BelongsTo::make('Order')
                ->sortable(),

            Date::make('Date')->readOnly(),

            new Panel('Information', [
                Text::make('Vat')->hideFromIndex(),
                Text::make('Name')->hideFromIndex(),
                Text::make('Country')->hideFromIndex(),
                Text::make('Address')->hideFromIndex(),
                Text::make('Zip')->hideFromIndex(),
                Text::make('Region')->hideFromIndex(),
                Text::make('Phone')->hideFromIndex(),
            ]),

            new Panel('Pricing', [
                Text::make('Tax')->readOnly(),
                Currency::make('Price Untaxed')->readOnly(),
                Currency::make('Price')->readOnly(),
            ]),

            HasMany::make('Products', 'invoiceProducts', InvoiceProduct::class),
        ];
    }
}
