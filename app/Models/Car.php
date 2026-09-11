<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand', 'model', 'year', 'engine', 'mileage', 'price_per_day', 'buyout_price', 'status', 'photo',
    ];

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
    public function maintenanceLogs()
    {
        return $this->hasMany(CarMaintenanceLog::class);
    }
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available' => 'Доступний',
            'rented' => 'В оренді',
            'sold' => 'Проданий',
            'maintenance' => 'На обслуговуванні',
            default => $this->status,
        };
    }
}
