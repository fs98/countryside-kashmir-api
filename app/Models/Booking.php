<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
        'city',
        'country',
        'persons',
        'adults',
        'children',
        'arrival_date',
        'days',
        'nights',
        'package_id',
        'user_id'
    ];

    /**
     * Get the package that is the booking for.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Get the user that created the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
