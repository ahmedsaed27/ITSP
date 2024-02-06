<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reals extends Model
{
    use HasFactory;

    protected $table = 'reals';

    protected $fillable = ['team_members_id' , 'title' , 'vedioLink' , 'description' , 'category_id'];

    public $timestamps = true;



    public function member() :BelongsTo{
        return $this->belongsTo(TeamMembers::class ,'team_members_id');
    }

    public function category():BelongsTo{
        return $this->belongsTo(Category::class , 'category_id');
    }
}
