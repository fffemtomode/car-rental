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
    public function bookedDates(): array
    {
        $dates = [];

        $this->deals()
            ->where('type', 'rental')
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->each(function ($deal) use (&$dates) {
                $period = \Carbon\CarbonPeriod::create($deal->start_date, $deal->end_date);
                foreach ($period as $date) {
                    $dates[] = $date->format('Y-m-d');
                }
            });

        return $dates;
    }
    public function photos()
    {
        return $this->hasMany(CarPhoto::class)->orderBy('position');
    }
}
