<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Feedbacks extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];


    public function getDisputeDescriptionAttribute($value)
    {
        return $value ?? ''; // If value is null, return an empty string
    }
    public function provider_info(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function seeker_info(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function job_info(): BelongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
}
