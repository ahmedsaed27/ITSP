<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Jobs extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = ['image' , 'postion' , 'discription' , 'job_level' ,
                            'job_type' , 'job_place' , 'range_salary',
                            'skills' , 'requirments' , 'categories_id' , 'departments_id'
                        ];

    public $timestamps = true;

    protected $casts = [
        'skills' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'departments_id');
    }

    public function getImageAttribute($imagePath){
        if(Storage::disk('jobs')->exists($imagePath)){
            return Storage::disk('jobs')->url($imagePath);
        }

        return null;
    }
}
