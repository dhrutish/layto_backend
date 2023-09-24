<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsFeed extends Model
{
    use HasFactory;
    protected $appends = ['image_url'];
    protected $hidden = ['created_at', 'updated_at'];
    public function getImageUrlAttribute($value)
    {
        return image_path($this->image);
    }

    public function industry_type()
    {
        return $this->hasOne(IndustryTypes::class, 'id', 'industry_types_id');
    }
}
