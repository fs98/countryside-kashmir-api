<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
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
        'pivot'
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
    public function destinationImages()
    {
        return $this->hasMany(DestinationImage::class);
    }

    /**
     * The packages that belong to the destination.
     */
    public function packages()
    {
        return $this->belongsToMany(Package::class);
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
            get: fn () => asset('storage/' . $this->image),
        );
    }
}
