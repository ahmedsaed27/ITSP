<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReview extends Model
{
    use HasFactory;

    protected $table = 'employee_review';

    protected $fillable = ['review' , 'employees_id' , 'month'];

    public $timestamps = true;

    public function employee(){
        return $this->belongsTo(Employees::class , 'employees_id');
    }
}
