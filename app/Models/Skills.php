<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Skills extends Model
{
    use HasFactory , LogsActivity;

    protected $table = 'Skills';

    protected $fillable = ['title' , 'description'];

    public $timestamps = true;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['title', 'description']);
    }

}
