<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type'
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
        'password' => 'hashed',
    ];

    ////////
    public function massages()
    {
        return $this->hasMany(massage::class);
    }

    ////////
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    ////////
    public function ratingsGiven()
    {
        return $this->hasMany(Rate::class, 'rater_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rate::class, 'rated_id');
    }

    public function averageRating()
    {
        return $this->ratingsReceived()->avg('rating');
    }

    public function numRating()
    {
        return $this->ratingsReceived()->count('rater_id');
    }

    //////
    public function services()
    {
        return $this->hasMany(service::class,'user_id');
    }

    ////////
    public function projects()
    {
        return $this->hasMany(Project::class,'user_id');
    }
}
