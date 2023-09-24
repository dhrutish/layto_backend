<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('valid_referral_code', function ($attribute, $value, $parameters, $validator) {
            $checkref = User::where('referral_code', $value)->whereIn('type', [3, 4])->available()->first();
            return $checkref !== null;
        });
        Validator::extend('validate_rating', function ($attribute, $value, $parameters, $validator) {
            return $value >= 0.5 && $value <= 5;
        });
    }
}
