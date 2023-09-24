<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Jobs extends Model
{
    use HasFactory;
    protected $hidden = ['created_at', 'updated_at', 'job_auto_close_days'];

    protected $appends = ['IsJobEditable'];

    public function scopeAvailable($query)
    {
        return $query->where('status', 1);
    }
    public function scopeClosed($query)
    {
        return $query->whereIn('status', [2, 3, 4, 6]);
    }
    public function scopeFSP($query)
    {
        return $query->where('status', 5);
    }
    public function scopePostedOn($query)
    {
        return $query->selectRaw("IF(is_reposted = 1, DATE_FORMAT(reposted_on, '%Y-%m-%d'), DATE_FORMAT(created_at, '%Y-%m-%d')) AS posted_on");
    }
    public function scopePostedOnWithTime($query)
    {
        return $query->selectRaw("IF(is_reposted = 1, DATE_FORMAT(reposted_on, '%Y-%m-%d %h:%i %p'), DATE_FORMAT(created_at, '%Y-%m-%d %h:%i %p')) AS posted_on");
    }
    public function scopeClosedOn($query)
    {
        return $query->selectRaw("IF(closed_at IS NOT NULL, DATE_FORMAT(closed_at, '%Y-%m-%d %h:%i %p'), NULL) as closed_on");
    }
    public function scopeFavoriteJobs($query, $user_id)
    {
        return $query->whereIn('id', function ($query) use ($user_id) {
            $query->select('job_id')->from(with(new Favorites)->getTable())->where('user_id', $user_id);
        });
    }



    function getGenderTextAttribute()
    {
        return ['Male', 'Female', 'Other'][$this->gender - 1];
    }
    function getExperienceTypeTextAttribute()
    {
        return ['Any', 'Fresher', 'Experienced'][$this->gender - 1];
    }
    function getIsJobEditableAttribute()
    {
        $count = JobApplications::where('job_id', $this->id)->count();
        return $count > 0 ? false : true;
    }




    public function category(): HasOne
    {
        return $this->hasOne(JobCategories::class, 'job_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function industry_types()
    {
        return $this->belongsTo(IndustryTypes::class);
    }
    public function payment_types()
    {
        return $this->belongsTo(PaymentTypes::class);
    }
    public function education()
    {
        return $this->belongsTo(Education::class);
    }
    public function availability(): BelongsTo
    {
        return $this->belongsTo(Availabilities::class, 'availabilities_id');
    }
    public function industry_type_info(): BelongsTo
    {
        return $this->belongsTo(IndustryTypes::class, 'industry_types_id');
    }
    public function location_info(): BelongsTo
    {
        return $this->belongsTo(Locations::class, 'locations_id');
    }
    public function education_info(): BelongsTo
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
    public function categories(): HasMany
    {
        return $this->hasMany(JobCategories::class, 'job_id');
    }
    public function skills(): HasMany
    {
        return $this->hasMany(JobSkills::class, 'job_id');
    }




    public function provider_feedbacks(): HasMany
    {
        return $this->hasMany(Feedbacks::class, 'user_id')->where('type', 2);
    }
    public function scopeProviderFeedbacksAvg($query)
    {
        return $query->withAvg('user.provider_feedbacks', 'rating');
    }




    public function favorites(): HasMany
    {
        return $this->hasMany(Favorites::class, 'job_id');
    }
    public function scopeIsJobFavorite()
    {
        return $this->favorites->where('user_id', auth('sanctum')->user()->id)->isNotEmpty();
    }
}
