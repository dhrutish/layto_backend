<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transactions extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'coin_expire_days'];

    // protected static function booted()
    // {
    //     static::retrieved(function ($model) {
    //         foreach ($model->attributes as $key => $value) {
    //             if (is_null($value)) {
    //                 $model->setAttribute($key, '');
    //             }
    //         }
    //     });
    // }

    public function job(): belongsTo
    {
        return $this->belongsTo(Jobs::class, 'job_id');
        // return $this->belongsTo(Jobs::class, 'job_id')->withDefault();
    }
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function refer_user(): belongsTo
    {
        return $this->belongsTo(User::class, 'refer_user_id');
    }
}
