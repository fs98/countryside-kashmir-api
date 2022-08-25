<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'duration',
        'price',
        'category_id',
        'persons',
        'keywords',
        'user_id',
        'author_id'
    ];

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
}
