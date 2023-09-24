<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Mews\Purifier\Casts\CleanHtmlInput;
// composer require mews/purifier

class SalaryTypes extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at'];

    // protected $casts = [
    //     'title_en' => CleanHtmlInput::class,
    //     'title_hi' => CleanHtmlInput::class,
    //     'title_gj' => CleanHtmlInput::class,
    // ];
}
