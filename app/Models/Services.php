<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = ['title' , 'icon' , 'description' , 'category_id'];

    public $timestamps = true;

    public function category(){
        return $this->belongsTo(Category::class , 'category_id');
    }
}
