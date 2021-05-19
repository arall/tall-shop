<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Orders';

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
                'Pending Payment' => 'warning',
                'Payment Completed' => 'info',
                'In preparation' => 'info',
                'Shipped' => 'success',
                'Received' => 'success',
                'On hold' => 'warning',
                'Canceled' => 'danger',
            ]),

            Select::make('Status')->options(\App\Models\Order::STATUSES)->onlyOnForms(),

            BelongsTo::make('User')
                ->sortable(),

            BelongsTo::make('Shipping Carrier', 'shippingCarrier', ShippingCarrier::class)
                ->sortable(),

            BelongsTo::make('Payment Method', 'paymentMethod', PaymentMethod::class)
                ->sortable(),

            DateTime::make('Created At')->readOnly()->sortable(),

            new Panel('Shipping Information', [
                Text::make('Track number')->hideFromIndex(),
                Text::make('Firstname')->hideFromIndex(),
                Text::make('Lastname')->hideFromIndex(),
                Text::make('Country')->hideFromIndex(),
                Text::make('Address')->hideFromIndex(),
                Text::make('Zip')->hideFromIndex(),
                Text::make('Region')->hideFromIndex(),
                Text::make('Phone')->hideFromIndex(),
            ]),

            new Panel('Pricing', [
                Text::make('Tax')->readOnly()->hideFromIndex(),
                Currency::make('Shipping Price Untaxed')->readOnly()->hideFromIndex(),
                Currency::make('Shipping Price')->readOnly()->hideFromIndex(),
                Currency::make('Payment Method Price Untaxed')->readOnly()->hideFromIndex(),
                Currency::make('Payment Method Price')->readOnly()->hideFromIndex(),
                Currency::make('Products Price Untaxed')->readOnly()->hideFromIndex(),
                Currency::make('Products Price')->readOnly()->hideFromIndex(),
                Currency::make('Total Price Untaxed')->readOnly()->hideFromIndex(),
                Currency::make('Total Price')->readOnly()->sortable(),
            ]),

            new Panel('Payment Information', [
                Text::make('Payment Ref')->hideFromIndex()->readOnly(),
                DateTime::make('Payment Date')->hideFromIndex()->readOnly(),
            ]),

            HasMany::make('Products', 'orderProducts', OrderProduct::class),
        ];
    }
}
