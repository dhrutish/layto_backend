<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifications extends Model
{
    use HasFactory;
    protected $hidden = ['is_read', 'updated_at'];
    protected $fillable = ['provider_id', 'user_id', 'job_id', 'title', 'description', 'type'];
    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function job(): BelongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
}
