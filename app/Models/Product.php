<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use Sluggable, HasFactory;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($product) {
            foreach ($product->images as $image) {
                $image->delete();
            }
        });
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get product cover image URL.
     *
     * @param  string $size
     * @return void
     */
    public function getCoverUrl(string $size)
    {
        return $this->cover ? $this->cover->getUrl($size) : asset('/storage/images/defaults/product.png');
    }

    /**
     * Calculate the total price with variant options.
     *
     * @param  array $optionIds
     * @return decimal
     */
    public function getPrice(array $optionIds = [])
    {
        $price = $this->price;
        foreach ($optionIds as $optionId) {
            $option = ProductVariantOption::find($optionId);
            if ($option) {
                $price += $option->price;
            }
        }

        return $price;
    }

    /**
     * Get options grouped by variant name.
     *
     * @return array
     */
    public function groupedOptions()
    {
        $result = [];
        if ($this->options) {
            foreach ($this->options as $option) {
                $result[$option->variant->id][] = $option;
            }
        }

        return $result;
    }

    /**
     * Get the product cover image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cover()
    {
        return $this->belongsTo(ProductImage::class, 'cover_id');
    }

    /**
     * Get the product images.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the product category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the product type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    /**
     * Get the product variants.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_options');
    }

    /**
     * Get the product variant options.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(ProductVariantOption::class);
    }
}
