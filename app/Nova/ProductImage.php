<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Avatar;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ProductImage::class;

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
            ID::make()->sortable(),

            BelongsTo::make('Product'),

            Image::make('Original Filename')
                ->store(function (Request $request, $model) {
                    $path = $request->file('original_filename')->store('/images/products/original', 'public');
                    return [
                        'original_filename' => basename($path),
                    ];
                })->onlyOnForms()->hideWhenUpdating(),

            Avatar::make('Filename')
                ->disk('public')
                ->thumbnail(function () {
                    return $this->getUrl('thumb') . '?t=' . strtotime("now");;
                })
                ->preview(function () {
                    return $this->getUrl('mid') . '?t=' . strtotime("now");
                })
                ->download(function ($request, $model, $disk) {
                    return Storage::disk($disk)->download($model->getPath('original'));
                })
                ->store(function (Request $request, $model) {
                    return function () use ($model, $request) {

                        $path = $request->file('filename')->store('/images/products/original', 'public');

                        $model->deleteFiles();
                        $model->original_filename = basename($path);
                        $model->save();
                        $model->createImageSizes();
                    };
                })
                ->deletable(false)
                ->hideWhenCreating(),
        ];
    }
}
