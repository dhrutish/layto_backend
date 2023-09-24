<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndustryTypes extends Model
{
    use HasFactory;
    protected $hidden = ['is_available', 'created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Categories', 'industry_types_id', 'id')->select('id', 'industry_types_id', 'title_en', 'title_hi', 'title_gj');
    }
}
