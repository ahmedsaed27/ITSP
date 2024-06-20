<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Projects extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = ['title' , 'images' , 'link' , 'description' , 'category_id'];

    public $timestamps = true;

    protected $casts = [
        'images' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class , 'category_id');
    }

    public function getImagesAttribute($images){

        $arr = json_decode($images);
        if(count($arr) > 0){
            foreach($arr as $image){
                $urls[] = Storage::disk('projects')->url($image);
            }
            return $urls;
        }

        return null;
    }
}
