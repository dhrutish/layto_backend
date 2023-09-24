<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Areas extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }

    public function scopeDefault($query)
    {
        return $query->where('type', 1);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(States::class, 'state_id');
    }
    public function city(): BelongsTo
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }
}
