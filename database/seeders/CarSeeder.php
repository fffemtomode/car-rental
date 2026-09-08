<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            ['brand' => 'Toyota', 'model' => 'Corolla', 'year' => 2021, 'price_per_day' => 900, 'buyout_price' => 450000, 'status' => 'available'],
            ['brand' => 'Volkswagen', 'model' => 'Golf', 'year' => 2020, 'price_per_day' => 850, 'buyout_price' => 420000, 'status' => 'available'],
            ['brand' => 'Skoda', 'model' => 'Octavia', 'year' => 2022, 'price_per_day' => 1000, 'buyout_price' => 500000, 'status' => 'available'],
            ['brand' => 'Renault', 'model' => 'Duster', 'year' => 2019, 'price_per_day' => 750, 'buyout_price' => 380000, 'status' => 'available'],
            ['brand' => 'BMW', 'model' => '320i', 'year' => 2021, 'price_per_day' => 1800, 'buyout_price' => 900000, 'status' => 'available'],
            ['brand' => 'Hyundai', 'model' => 'Tucson', 'year' => 2020, 'price_per_day' => 1100, 'buyout_price' => 550000, 'status' => 'available'],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
