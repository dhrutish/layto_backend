<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkills extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'skills_id'];
    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skills::class, 'skills_id');
    }
}
