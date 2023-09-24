<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpamRequests extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    public function job_info(): BelongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
}
