<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSkills extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    public function job(): BelongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skills::class, 'skills_id');
    }
}
