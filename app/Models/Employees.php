<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employees extends Authenticatable
{

    use HasFactory , Notifiable;

    protected $table = 'employees';

    protected $guard_name = 'hr';

    protected $fillable = [
        'user_id','phone' , 'address' , 'gander' , 'college' ,
        'university' , 'Specialization' , 'position_type' , 'skils' , 'departments_id'
    ];

    public $timestamps = true;

    protected $casts = [
        'skils' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

}
