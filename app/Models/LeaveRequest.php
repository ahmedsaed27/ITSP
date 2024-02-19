<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'leave_request';

    protected $fillable = ['date', 'employees_id' , 'note' , 'status'];

    public $timestamps = true;

    public function employee(){
        return $this->belongsTo(Employees::class , 'employees_id');
    }

    public function getStatusAttribute($value)
    {
        $value = match($value){
            0 => 'waiting',
            1 => 'acceptable',
            2 => 'rejected',
        };

        return $value;
    }

    public static function boot() {

		parent::boot();

		static::updated(function($model) {

            if($model->status == 'acceptable'){

                $vacation = Vacations::where('employees_id', $model->employees_id)->first();

                list($startDate, $endDate) = explode(' - ', $model->date);

                $carbonStartDate = Carbon::createFromFormat('d/m/Y', $startDate);
                $carbonEndDate = Carbon::createFromFormat('d/m/Y', $endDate);

                $dayesCount = $carbonStartDate->diffInDays($carbonEndDate);


                Vacations::updateOrCreate(
                    ['employees_id' => $model->employees_id],
                    [
                        'total' => 21,
                        // 'public_holidays' => 0,
                        'expire' => optional($vacation)->expire + $dayesCount,
                        'available' => optional($vacation)->available ?? 21 - $dayesCount,
                    ]
                );


            }
		});
	}
}
