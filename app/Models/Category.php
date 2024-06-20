<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name' , 'image' , 'description'];

    public $timestamps = true;

    public function getImageAttribute($imagePath){
        if(Storage::disk('category')->exists($imagePath)){
            return Storage::disk('category')->url($imagePath);
        }

        return null;
    }
}
