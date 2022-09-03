<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the activities for the author.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the blogs for the author.
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * Get the destinations for the author.
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
