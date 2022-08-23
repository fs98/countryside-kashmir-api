<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get the user that created the destination.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
