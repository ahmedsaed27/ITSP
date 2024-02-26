<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function App\Helpers\calculation;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $table = 'leave_request';

    protected $fillable = ['date', 'user_id', 'note', 'status' , 'answer'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusAttribute(int $value)
    {
        $value = match ($value) {
            0 => 'waiting',
            1 => 'acceptable',
            2 => 'rejected',
        };

        return $value;
    }

    public static function boot()
    {

        parent::boot();

        static::updating(function ($model) {

            if ($model->status == 'acceptable') {

                $vacation = $model->user->vacations;

                $dayesCount = calculation($model->date);


                Vacations::updateOrCreate(
                    ['user_id' => $model->user_id],
                    [
                        'leave_request_id' => $model->leave_request_id,
                        'total' => 21,
                        'expire' => optional($vacation)->expire + $dayesCount,
                        'available' => optional($vacation)->available - $dayesCount,
                    ]
                );
            }
        });

        static::created(function ($model) {

            if ($model->status == 'acceptable') {

                $vacation = $model->user->vacations;

                $dayesCount = calculation($model->date);


                Vacations::updateOrCreate(
                    ['user_id' => $model->user_id],
                    [
                        'leave_request_id' => $model->leave_request_id,
                        'total' => 21,
                        'expire' => optional($vacation)->expire + $dayesCount,
                        'available' => optional($vacation)->available - $dayesCount,
                    ]
                );
            }
        });
    }
}
