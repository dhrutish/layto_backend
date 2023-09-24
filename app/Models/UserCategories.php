<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCategories extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = ['user_id', 'categories_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
