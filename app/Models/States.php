<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }

    public function cities()
    {
        return $this->hasMany('App\Models\Cities', 'state_id', 'id')->select('id', 'state_id', 'title_en', 'title_hi', 'title_gj');
    }
}
