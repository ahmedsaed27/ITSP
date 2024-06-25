<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , LogsActivity , HasRoles;

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



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name']);
    }

    public function getRoleNamesAttribute() :string
    {
        return $this->roles->pluck('name')->join(',');
    }

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
