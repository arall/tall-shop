<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Number;

class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Products';

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
        'id', 'name', 'description',
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

            BelongsTo::make('Type', 'type', ProductType::class)
                ->sortable(),

            BelongsTo::make('Category', 'category', ProductCategory::class)
                ->sortable(),

            Text::make('Name')
                ->sortable(),

            Text::make('Slug')
                ->readonly()
                ->sortable(),

            Text::make('Sku')
                ->sortable(),

            Currency::make('Price')
                ->sortable(),

            Currency::make('Price Compare')
                ->sortable(),

            Number::make('Weight')
                ->sortable(),

            Textarea::make('Description'),

            HasMany::make('Images', 'images', ProductImage::class),

            HasMany::make('Options', 'options', ProductVariantOption::class),
        ];
    }
}
