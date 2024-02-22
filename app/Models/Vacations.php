<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacations extends Model
{
    use HasFactory;

    protected $table = 'vacations';

    protected $fillable = ['total' , 'expire' , 'available' , 'user_id'];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

}
