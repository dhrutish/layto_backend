<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cities extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(States::class, 'state_id');
    }

    public function areas()
    {
        return $this->hasMany('App\Models\Areas', 'city_id', 'id')->select('id', 'city_id', 'title_en', 'title_hi', 'title_gj');
    }
}
