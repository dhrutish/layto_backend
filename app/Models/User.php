<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Api\AuthenticationController;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            do {
                $referralCode = Str::random(8);
            } while (static::where('referral_code', $referralCode)->exists());
            $user->referral_code = $referralCode;
        });
    }


    protected $appends = ['image_url'];
    public function getImageUrlAttribute($value)
    {
        return image_path($this->image);
    }

    public function getAboutAttribute($value)
    {
        return $value ?? '';
    }
    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ?? '';
    }
    public function getSocialLoginIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getFcmTokenAttribute($value)
    {
        return $value ?? '';
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', 1);
    }
    public function scopetypeprovider($query)
    {
        return $query->where('type', 3);
    }
    public function scopetypeseeker($query)
    {
        return $query->where('type', 4);
    }

    public function resume(): HasOne
    {
        return $this->hasOne(Resumes::class, 'user_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany('App\Models\Locations', 'user_id', 'id')->latest();
    }
    public function location(): HasOne
    {
        return $this->hasOne('App\Models\Locations', 'user_id', 'id')->latest();
    }

    public function other_info(): HasOne
    {
        return $this->hasOne(UserOtherInfo::class, 'user_id');
    }
    public function id_proof_details()
    {
        return $this->hasOne('App\Models\IdProof', 'user_id', 'id')->select('id', 'user_id', 'id_number', 'front_image', 'back_image', 'status', DB::raw("CONCAT('" . url('storage/app/public/admin/assets/images/proofs') . "/', front_image) AS front_image_url"), DB::raw("CONCAT('" . url('storage/app/public/admin/assets/images/proofs') . "/', back_image) AS back_image_url"),);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(UserCategories::class, 'user_id');
    }
    public function skills(): HasMany
    {
        return $this->hasmany(UserSkills::class, 'user_id');
    }

    public function work_experience(): HasMany
    {
        return $this->hasMany(UserWorkExperience::class, 'user_id');
    }
    public function current_working(): HasOne
    {
        return $this->hasOne(UserWorkExperience::class, 'user_id')->where('is_currently_working', 1);
    }
    public function job_application()
    {
        return $this->hasMany(JobApplications::class, 'user_id');
    }
    public function getLoginTypeTextAttribute()
    {
        return ['Mobile', 'Google', 'facebook', 'Apple', 'Email'][$this->login_type - 1];
    }
    public function provider_feedbacks(): HasMany
    {
        return $this->hasMany(Feedbacks::class, 'provider_id')->where('type', 2);
    }
    public function getProviderFeedbacksAvgAttribute()
    {
        return floatval(number_format($this->provider_feedbacks()->avg('rating'), 1));
    }
    public function seeker_feedbacks(): HasMany
    {
        return $this->hasMany(Feedbacks::class, 'user_id')->where('type', 1);
    }
    public function getSeekerFeedbacksAvgAttribute()
    {
        return floatval(number_format($this->seeker_feedbacks()->avg('rating'), 1));
    }
    public function scopeFeedbacksAvg()
    {
        return floatval(number_format($this->type == 4 ? $this->seeker_feedbacks()->avg('rating') : $this->provider_feedbacks()->avg('rating'), 1));
    }
    public function avljobs(): HasMany
    {
        return $this->hasMany(Jobs::class,  'user_id');
    }
    public function getTotalJobsAttribute()
    {
        return count($this->avljobs);
    }
    public function candidatefavorites()
    {
        return $this->hasMany(Favorites::class, 'user_id');
    }
    public function scopeIsCandidateFavorite()
    {
        return $this->candidatefavorites->where('provider_id', auth('sanctum')->user()->id)->isNotEmpty();
    }
    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availabilities::class, 'availabilities_id');
    }

    public function scopeFavoriteSeekers($query, $user_id)
    {
        return $query->whereIn('id', function ($query) use ($user_id) {
            $query->select('user_id')->from(with(new Favorites)->getTable())->where('provider_id', $user_id);
        });
    }
    // public function scopeprofile_complete()
    // {
    //     return (new AuthenticationController)->isProfileComplete($this->id);
    // }
}
