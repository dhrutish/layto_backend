<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\NewsFeedsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\MasterMenusController;
use App\Http\Controllers\Api\LikesController;
use App\Http\Controllers\Api\FeedbacksController;
use App\Http\Controllers\Api\SkillsController;
use App\Http\Controllers\Api\AreasController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\JobsController;
use App\Http\Controllers\Api\ProposalsController;
use App\Http\Controllers\Api\JobSeekersController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['api.validate'])->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('verify-mobile', 'sendtextotp');
        Route::post('verify-email', 'sendemailotp');
        Route::post('forgot-password', 'forgot_password');
        Route::post('change-password', 'change_password');
        Route::post('google-login', 'google_login');
        Route::post('apple-login', 'apple_login');
        Route::post('validate-details', 'checkvaliddata');
    });

    Route::get('cmspages', [AdminController::class, 'cmspages']);

    // Status: 0 = Errors || 1 = Success || 2 = Check Profile || 3 = Insufficient Coins || 4 = Spam request already created
    Route::middleware(['ensure_authenticate'])->group(function () {
        Route::get('logout', [AuthenticationController::class, 'logout']);
        Route::get('getuser', [AuthenticationController::class, 'getuser']);
        Route::get('coins-settings', [AuthenticationController::class, 'coins_settings']);

        Route::post('create-area', [AreasController::class,'store']);
        Route::post('create-skill', [SkillsController::class,'store']);

        Route::controller(MasterMenusController::class)->group(function () {
            Route::get('education-list', 'education_list');
            Route::get('payment-type-list', 'payment_type_list');
            Route::get('availabilities-list', 'availabilities_list');
            Route::get('notes-{type}', 'notes');
            Route::get('industry-types', 'industry_types');
            Route::post('categories', 'categories');
            Route::post('skills', 'skills');
            Route::get('states', 'states');
            Route::post('cities', 'cities');
            Route::post('areas', 'areas');
            Route::get('faqs-list', 'faqs_list');
            Route::get('notifications', 'notifications');
        });
        Route::controller(ProfileController::class)->group(function () {
            Route::post('update-profile', 'update_profile');
            Route::post('basic-details', 'edit_basic_details');
            Route::post('additional-details', 'edit_additional_details');
            Route::post('edit-address', 'edit_address');
            Route::post('edit-aadhar', 'edit_aadhar_details');
            Route::post('edit-job-preference', 'edit_job_preference');
            Route::post('edit-job-expectation', 'edit_job_expectation');
            Route::post('add-experience', 'add_experience');
            Route::post('edit-experience', 'edit_experience');
            Route::get('experience-list', 'experience_list');
            Route::get('delete-experience-{id}', 'delete_experience');
            Route::post('edit-total-experience', 'edit_total_experience');
            Route::post('edit-education', 'edit_education');
            Route::post('add-company-location', 'add_company_location');
            Route::get('edit-company-location-{id}', 'edit_company_location');
            Route::post('update-company-location', 'update_company_location');
            Route::get('delete-company-location-{id}', 'delete_company_location');
            Route::post('edit-about-us', 'edit_about_us');
            Route::get('switch-profile', 'switch_profile');
        });
        Route::middleware(['check_profile'])->group(function () {
            Route::get('razorpay', [AdminController::class, 'razorpay']);
            Route::controller(UserController::class)->group(function () {
                Route::get('refer-earn-history', 'refer_earn_history');
                Route::get('user-locations', 'user_locations');
                Route::get('provider-details-{id}', 'provider_details');
            });
            Route::controller(SubscriptionController::class)->group(function () {
                Route::get('subscriptions-list', 'subscriptions_list');
                Route::post('buy-coins', 'buy_coins');
                Route::get('coins-history-1', 'coins_history');
                Route::get('coins-history-2', 'coins_history');
            });
            Route::controller(NewsFeedsController::class)->group(function () {
                Route::get('news-feed-list', 'news_feed_list');
                Route::get('top-news-feed-list', 'top_news_feed_list');
                Route::get('news-details-{id}', 'news_details');
            });
            Route::controller(JobsController::class)->group(function () {
                Route::get('jobs-list', 'jobs_list');
                Route::post('post-job', 'post_job');
                Route::post('update-job', 'post_job');
                Route::get('close-job-{id}', 'close_job');
                Route::get('edit-job-{id}', 'edit_job');
                Route::post('spam-job', 'spam_job');
            });
            Route::controller(LikesController::class)->group(function () {
                Route::post('like-job', 'like_job');
                Route::post('unlike-job', 'unlike_job');
                Route::get('liked-jobs', 'liked_jobs');
                Route::post('like-candidate', 'like_candidate');
                Route::post('unlike-candidate', 'unlike_candidate');
                Route::get('liked-candidates', 'liked_candidates');
            });
            Route::controller(FeedbacksController::class)->group(function () {
                Route::get('provider-feedbacks', 'provider_feedbacks_list');
                Route::get('seeker-feedbacks', 'seeker_feedbacks_list');
                Route::post('raise-dispute', 'raise_dispute');
                Route::post('job-feedback', 'job_feedback');
                Route::post('candidate-feedback', 'candidate_feedback');
            });
            Route::controller(ProposalsController::class)->group(function () {
                Route::post('manage-proposal', 'manage_proposal');
                Route::post('seeker-proposal', 'seeker_proposal');
                Route::post('apply-job', 'job_proposal');
                Route::post('pending-proposal', 'pending_proposal');
                Route::post('accept-proposal', 'accept_proposals');
                Route::post('reject-proposal', 'reject_proposals');
                Route::get('applied-jobs', 'applied_jobs');
                Route::get('accepted-jobs', 'accepted_jobs');
                Route::get('closed-jobs', 'closed_jobs');
            });
            Route::controller(HomeController::class)->group(function () {
                Route::get('home-feeds', 'home_feeds');
                // Route::post('filter', 'filter');
                Route::post('search-filter-provider', 'search_filter_seekers');
                Route::post('search-filter-seeker', 'search_filter_jobs');
            });
            Route::controller(JobSeekersController::class)->group(function () {
                Route::get('seeker-work-experience', 'work_experience_list');
                Route::post('candidate-details', 'candidate_details');
            });
        });
    });
});
