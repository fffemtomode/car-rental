<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarMaintenanceLog extends Model
{
    protected $fillable = ['car_id', 'type', 'date', 'mileage_at_service', 'note'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
