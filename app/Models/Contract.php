<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = ['deal_id', 'file_path', 'signed_at'];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }
}
