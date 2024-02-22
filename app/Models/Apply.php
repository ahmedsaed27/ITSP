<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    use HasFactory;

    protected $table = 'apply';

    protected $fillable = ['jobs_id' , 'applicant_id' , 'cv' , 'years_experience'];

    public $timestamps = true;


    public function job(){
        return $this->belongsTo(Jobs::class , 'jobs_id');
    }

    public function applicant(){
        return $this->belongsTo(Applicant::class , 'applicant_id');
    }
}
