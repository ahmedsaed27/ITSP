<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalInterviewStatus extends Model
{
    use HasFactory;

    protected $table = 'final_interview_status';

    protected $fillable = ['interview_date_id' , 'date' , 'status' , 'notes' , 'message'];

    public function interview(){
        return $this->belongsTo(InterviewDate::class , 'interview_date_id');
    }
}
