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
     * Calculate the min total price with variant options.
     *
     * @return decimal
     */
    public function getMinPrice()
    {
        $price = $this->price;
        $mins = [];

        foreach ($this->options as $option) {
            if (!isset($mins[$option->product_variant_id]) || $mins[$option->product_variant_id] >= $option->price) {
                $mins[$option->product_variant_id] = $option->price;
            }
        }

        foreach ($mins as $min) {
            $price += $min;
        }

        return $price;
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
            foreach ($this->options()->orderBy('product_variant_id', 'ASC')->orderBy('price', 'ASC')->get() as $option) {
                $result[$option->variant->id][] = $option;
            }
        }

        return $result;
    }

    /**
     * Search products by string.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search = '')
    {
        if (!$search) {
            return $query;
        }

        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->orWhere('sku', 'like', '%' . $search . '%');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(ProductCategory::class, 'product_category', 'product_id', 'category_id');
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
