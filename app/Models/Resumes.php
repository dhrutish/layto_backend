<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resumes extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $appends = ['image_url'];
    public function getImageUrlAttribute($value)
    {
        return image_path($this->image);
    }
}
