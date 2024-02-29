<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'review';

    protected $fillable = ['apply_id'  , 'status' , 'note'];

    public $timestamps = true;

    public function apply(){
        return $this->belongsTo(Apply::class , 'apply_id');
    }

    public function interview(){
        return $this->hasOne(InterviewDate::class , 'review_id');
    }
}
