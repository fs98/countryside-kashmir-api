<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the messages for the user.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get the bookings for the user.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the destinations for the user.
     */
    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }

    /**
     * Get the destinations images for the user.
     */
    public function destinationImages()
    {
        return $this->hasMany(DestinationImage::class);
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the destinations images for the user.
     */
    public function activityImages()
    {
        return $this->hasMany(ActivityImage::class);
    }

    /**
     * Get the blogs for the user.
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * Get the packages for the user.
     */
    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Get the destinations images for the user.
     */
    public function packageImages()
    {
        return $this->hasMany(PackageImage::class);
    }
}
