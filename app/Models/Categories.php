<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categories extends Model
{
    use HasFactory;
    protected $hidden = ['is_available', 'created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }
    public function industry_type(): BelongsTo
    {
        return $this->belongsTo(IndustryTypes::class, 'industry_types_id');
    }
    public function skills(): HasMany
    {
        return $this->hasMany(Skills::class, 'categories_id');
    }
}
