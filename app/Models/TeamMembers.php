<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
    use HasFactory;

    protected $table = 'team_members';

    protected $fillable = ['fullName' , 'position' , 'description' , 'image'];

    public $timestamps = true;

}
