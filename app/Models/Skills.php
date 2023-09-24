<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skills extends Model
{
    use HasFactory;
    protected $hidden = ['is_available', 'created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }
    public function scopeDefault($query)
    {
        return $query->where('type', 1);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
