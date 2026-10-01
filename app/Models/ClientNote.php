<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNote extends Model
{
    protected $fillable = ['user_id', 'created_by', 'note'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
