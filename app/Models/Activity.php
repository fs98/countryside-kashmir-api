<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
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
        'keywords',
        'user_id',
        'author_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'author_id',
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
     * Get the user that created the destination.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the author that owns the destination.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the images for the destination.
     */
    public function activityImages()
    {
        return $this->hasMany(ActivityImage::class);
    }

    /**
     * Get the activity's description.
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
