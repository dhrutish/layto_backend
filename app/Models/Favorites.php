<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorites extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeActiveJob($query)
    {
        return $query->whereHas('job', function ($query) {
            $query->where('status', 1);
        });
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
