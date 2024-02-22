<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citys extends Model
{
    use HasFactory;

    protected $table = 'citys';

    protected $fillable = ['city' , 'admin_name' , 'capital'];

    public $timestamps = true;
}
