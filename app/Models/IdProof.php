<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdProof extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeTypeProvider($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->where('type', 3);
        });
    }

    public function scopeTypeSeeker($query)
    {
        return $query->whereHas('user', function ($query) {
            $query->where('type', 4);
        });
    }
}
