<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = ['image' , 'postion' , 'discription' , 'job_level' ,
                            'job_type' , 'job_place' , 'range_salary' 
                             , 'skills' , 'requirments'
                            ];

    public $timestamps = true;                        

    protected $casts = [
        'skills' => 'array'
    ];

}
