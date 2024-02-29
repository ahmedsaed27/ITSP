<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewDate extends Model
{
    use HasFactory;

    protected $table = 'interview_date';
    

    protected $fillable = ['review_id' , 'task' , 'mail_to' , 'attend' , 'date'];

    public function review(){
        return $this->belongsTo(Review::class , 'review_id');
    }

    public function FinalInterview(){
        return $this->belongsTo(InterviewDate::class , 'interview_date_id');
    }
}
