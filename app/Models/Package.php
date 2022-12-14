<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'image_alt',
        'days',
        'nights',
        'price',
        'category_id',
        'persons',
        'keywords',
        'user_id',
        'author_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'category_id',
        'user_id',
        'author_id',
        'pivot',
        'image'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'image_url',
    ];

    /**
     * Get the category that owns the package.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that created the package.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the author that owns the package.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * The destinations that belong to the package.
     */
    public function destinations()
    {
        return $this->belongsToMany(Destination::class);
    }

    /**
     * Get the images for the package.
     */
    public function packageImages()
    {
        return $this->hasMany(PackageImage::class);
    }

    /**
     * Get the destinations's description.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
        );
    }

    /**
     * Get the slide's image url.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => (str_starts_with($this->image, 'https')
                ? $this->image
                : asset('storage/' . $this->image)
            ),
        );
    }

    /**
     * Interact with the blog's name and slug.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => [
                'name' => $value,
                'slug' => Str::slug($value),
            ],
        );
    }
}
