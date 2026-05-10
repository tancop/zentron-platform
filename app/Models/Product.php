<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Unguarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Vite;
use Laravel\Scout\Searchable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

#[Table("Product")]
#[Unguarded]
class Product extends Model implements HasMedia
{
    use Searchable;
    use InteractsWithMedia;

    static array $fallbackImageUrls;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        parent::__construct();

        if (!isset(self::$fallbackImageUrls)) {
            self::$fallbackImageUrls = [
                Vite::asset('resources/images/ps5.jpg'),
                Vite::asset('resources/images/deck.jpg'),
                Vite::asset('resources/images/iphone.jpg'),
                Vite::asset('resources/images/ram.jpg'),
            ];
        }
    }

    public function fallbackImageUrl(): string
    {
        return self::$fallbackImageUrls[$this->id % (count(self::$fallbackImageUrls))];
    }

    public function previewUrl(): string
    {
        $url = $this->getFirstMediaUrl('images', 'preview');
        if ($url == '') {
            return $this->fallbackImageUrl();
        }
        return $url;
    }

    public function previewUrlAvif(): string
    {
        $media = $this->getFirstMedia('images');
        if (!$media || !$media->hasGeneratedConversion('preview-avif')) {
            return $this->previewUrl();
        }

        $url = $this->getFirstMediaUrl('images', 'preview-avif');
        if ($url == '') {
            return $this->fallbackImageUrl();
        }
        return $url;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, "ProductCategory",
            "product_id", "category_id");
    }

    public function isInCategory(int $categoryId): bool
    {
        return $this->categories->contains($categoryId);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    protected function casts(): array
    {
        return [
            "name" => "string",
            "price" => "decimal:2",
            "description" => "string",
            "color" => "string",
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'brand' => $this->brand()->value('name'),
            'categories' => $this->categories()
                ->pluck('name')
                ->implode(' '),
        ];
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();

        if (function_exists('imageavif')) {
            $this
                ->addMediaConversion('preview-avif')
                ->fit(Fit::Contain, 300, 300)
                ->format('avif');
        }

        $this
            ->addMediaConversion('hero')
            ->fit(Fit::Contain, 600, 600);

        if (function_exists('imageavif')) {
            $this
                ->addMediaConversion('hero-avif')
                ->fit(Fit::Contain, 600, 600)
                ->format('avif');
        }
    }
}
