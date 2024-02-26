<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

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


    public function employee()
    {
        return $this->hasOne(Employees::class, 'user_id');
    }

    public function vacations()
    {
        return $this->hasOne(Vacations::class, 'user_id');
    }

    public function leave()
    {
        return $this->hasMany(LeaveRequest::class, 'user_id');
    }

    // public function getTypeAttribute($value)
    // {
    //     $value = match ($value) {
    //         0 => 'Admin',
    //         1 => 'Employee',
    //         2 => 'Hr',
    //     };

    //     return $value;
    // }


    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {

            Vacations::create([
                'user_id' => $model->id,
                'total' => 21,
                'expire' => 0,
                'available' => 21,
            ]);

        });
    }



}
