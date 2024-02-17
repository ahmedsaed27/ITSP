<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacations extends Model
{
    use HasFactory;

    protected $table = 'vacations';

    protected $fillable = ['total' , 'expire' , 'available' , 'employees_id' , 'from' , 'to'];

    public $timestamps = true;

    public function employee(){
        return $this->belongsTo(Employees::class , 'employees_id');
    }

}
