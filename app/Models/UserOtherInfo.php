<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserOtherInfo extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];
    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availabilities::class, 'availabilities_id');
    }
    public function education(): BelongsTo
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
    public function industry(): BelongsTo
    {
        return $this->belongsTo(IndustryTypes::class, 'industry_types_id');
    }
}
