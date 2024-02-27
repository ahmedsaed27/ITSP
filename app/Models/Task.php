<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'task';

    protected $fillable = ['title' , 'requirement' , 'status' , 'start' , 'end' , 'user_id'];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
}
