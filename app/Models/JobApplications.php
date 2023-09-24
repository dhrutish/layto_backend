<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplications extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function provider_info(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function job_info(): BelongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeActiveJob($query)
    {
        return $query->whereHas('job_info', function ($query) {
            $query->where('status', 1);
        });
    }
    public function scopeClosedJob($query)
    {
        return $query->whereHas('job_info', function ($query) {
            $query->where('status', 2);
        });
    }
    public function scopeAppliedOn($query)
    {
        return $query->selectRaw("IF(created_at IS NOT NULL, DATE_FORMAT(created_at, '%Y-%m-%d'), NULL) as applied_on");
    }
    public function scopeAppliedOnWithTime($query)
    {
        return $query->selectRaw("IF(created_at IS NOT NULL, DATE_FORMAT(created_at, '%Y-%m-%d %h:%i %p'), NULL) as applied_on");
    }
}
