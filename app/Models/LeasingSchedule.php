<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeasingSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['deal_id', 'payment_date', 'amount', 'status'];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
