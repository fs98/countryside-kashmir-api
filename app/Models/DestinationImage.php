<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'image_alt',
        'destination_id',
        'user_id',
    ];

    /**
     * Get the destination that owns the image.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
