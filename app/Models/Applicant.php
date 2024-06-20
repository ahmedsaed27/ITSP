<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Applicant extends Model
{
    use HasFactory;

    protected $table = 'applicant';

    protected $fillable = ['cv' , 'phone' , 'name' , 'email' , 'password' , 'phone' ,'gender', 'citys_id' , 'area' , 'birthYear' ,'gender' , 'images'];

    protected $hidden = [
        'password'
    ];

    public $timestamps = true;

    public function city(){
        return $this->belongsTo(Citys::class , 'citys_id');
    }

    public function applies(){
        return $this->hasMany(Apply::class , 'applicant_id');
    }

    public function getImagesAttribute($imagePath){
        if(Storage::disk('applicant')->exists($imagePath)){
            return Storage::disk('applicant')->url($imagePath);
        }

        return null;
    }

    public function getCvAttribute($cv){
        if(Storage::disk('applicant')->exists($cv)){
            return Storage::disk('applicant')->url($cv);
        }

        return null;
    }
}
