<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;

    protected $table = 'applicant';

    protected $fillable = ['cv' , 'phone' , 'name' , 'email' , 'password' , 'phone' ,'gender', 'citys_id' , 'area' , 'birthYear' ,'gender' , 'images'];

    public $timestamps = true;

    public function city(){
        return $this->belongsTo(Citys::class , 'citys_id');
    }

    public function applies(){
        return $this->hasMany(Apply::class , 'applicant_id');
    }
}
