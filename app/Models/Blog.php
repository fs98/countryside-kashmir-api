<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image',
        'image_alt',
        'keywords',
        'user_id',
        'author_id',
        'published_at'
    ];

    /**
     * Get the user that created the blog.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the author that owns the blog.
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
