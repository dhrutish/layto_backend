<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }
}
