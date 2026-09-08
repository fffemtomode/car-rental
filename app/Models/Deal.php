<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'car_id', 'type', 'status', 'start_date', 'end_date', 'total_price', 'leasing_months',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function leasingSchedules()
    {
        return $this->hasMany(LeasingSchedule::class);
    }
}
