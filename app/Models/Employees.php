<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employees extends Authenticatable
{

    use HasFactory , Notifiable;

    protected $table = 'employees';

    protected $guard_name = 'hr';

    protected $fillable = ['name' , 'email' , 'password' , 'phone' , 'address' , 'gander' , 'education' , 'position_type' , 'skils' , 'departments_id'];

    public $timestamps = true;

    protected $casts = [
        'address' => 'array',
        'skils' => 'array',
    ];

    public function department(){
        return $this->belongsTo(Departments::class , 'departments_id');
    }
}
