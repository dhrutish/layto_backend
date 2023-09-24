<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function getUrlAttribute($value)
    {
        return $value ?? ''; // If value is null, return an empty string
    }

    public function states()
    {
        return $this->belongsTo(States::class, 'state_id');
    }
    public function cities()
    {
        return $this->belongsTo(Cities::class, 'city_id');
    }
    public function areas()
    {
        return $this->belongsTo(Areas::class, 'area_id');
    }

}
