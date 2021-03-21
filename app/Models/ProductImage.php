<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Storage path.
     */
    const PATH = 'images/products/';

    /**
     * Extension.
     */
    const EXT = 'jpg';

    /**
     * Image sizes.
     *
     * @var array
     */
    const SIZES = [
        'thumb' => [
            'width'  => 100,
            'height' => 100,
        ],
        'mid' => [
            'width'  => 384,
            'height' => 278,
        ],
        'large' => [
            'width'  => 1920,
            'height' => 1080,
        ],
        'original' => [],
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($image) {
            $image->filename = Str::random(16) . '.' . self::EXT;
        });

        static::created(function ($image) {
            $image->createImageSizes();
            if (!$image->product->cover) {
                $image->product->cover()->associate($image)->save();
            }
        });

        static::deleting(function ($image) {
            $image->deleteFiles();
        });
    }

    /**
     * Get image url.
     *
     * @param  string $size
     * @return string
     */
    public function getUrl(string $size = 'thumb')
    {
        return '/storage/' . $this->getRelativePath($size);
    }

    /**
     * Get image absolute path.
     *
     * @param  string $size
     * @return string
     */
    public function getPath(string $size)
    {
        return storage_path('app/public/' . $this->getRelativePath($size));
    }

    /**
     * Get image relative path.
     *
     * @param  string $size
     * @return void
     */
    private function getRelativePath(string $size)
    {
        return self::PATH . $size . '/' . $this->getFilename($size);
    }

    /**
     * Get the image filename based on it's size.
     *
     * @param  string $size
     * @return void
     */
    private function getFilename(string $size)
    {
        return $size == 'original' ? $this->original_filename : $this->filename;
    }

    /**
     * Make all the image sub-sizes.
     */
    public function createImageSizes()
    {
        $this->createSizeDirectories();

        // Image
        $image = Image::make($this->getPath('original'))->encode(self::EXT, 90);

        // Large
        $image->resize(self::SIZES['large']['width'], self::SIZES['large']['height'], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save($this->getPath('large'));

        // Mid
        $image->fit(self::SIZES['mid']['width'], self::SIZES['mid']['height']);
        $image->save($this->getPath('mid'));

        // Thumb
        $image->fit(self::SIZES['thumb']['width'], self::SIZES['thumb']['height']);
        $image->save($this->getPath('thumb'));
    }

    /**
     * Make the paths for different sizes
     */
    private function createSizeDirectories()
    {
        foreach (self::SIZES as $size => $sizes) {
            Storage::makeDirectory('public/' . self::PATH . $size);
        }
    }

    /**
     * Delete associated image files.
     */
    public function deleteFiles()
    {
        foreach (self::SIZES as $size => $sizes) {
            Storage::delete('public/' . $this->getRelativePath($size));
        }
    }

    /**
     * Get the related product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
