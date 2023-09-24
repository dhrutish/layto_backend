<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    use HasFactory;
    protected $appends = ['file_url'];
    protected $hidden = ['created_at', 'updated_at'];
    public function getFileUrlAttribute($value)
    {
        return image_path($this->file);
    }
    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }
}
