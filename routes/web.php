<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PlansController;
use App\Http\Controllers\Admin\StatesController;
use App\Http\Controllers\Admin\CitiesController;
use App\Http\Controllers\Admin\AreasController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\AvailabilitiesController;
use App\Http\Controllers\Admin\PaymentTypesController;
use App\Http\Controllers\Admin\IndustryTypesController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\SkillsController;
use App\Http\Controllers\Admin\AdvertisingsController;
use App\Http\Controllers\Admin\NewsFeedsController;
use App\Http\Controllers\Admin\NotesController;
use App\Http\Controllers\Admin\JobProvidersController;
use App\Http\Controllers\Admin\JobSeekersController;
use App\Http\Controllers\Admin\IdProofsController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\Admin\FeedbacksController;
use App\Http\Controllers\Admin\NottifyUsersController;
use App\Http\Controllers\Admin\JobsController;
use App\Http\Controllers\Admin\SpamRequestsController;
// use App\Http\Controllers\Admin\SalaryTypesController;
// use App\Http\Controllers\Admin\JobTypesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('messaging', [App\Http\Controllers\MessagingController::class, 'index']);


Route::get('/', function () {
    return redirect('admin');
});

Route::get('admin', [AuthenticationController::class, 'index'])->name('admin.login');
Route::post('admin/check', [AuthenticationController::class, 'checkadmin'])->name('admin.check');
Route::get('forgot-password', [AuthenticationController::class, 'forgotpassword'])->name('password.forgot');
Route::post('forgot-password/check', [AuthenticationController::class, 'sendpassword'])->name('password.send');

Route::group(['middleware' => 'AdminMiddleware'], function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'sub-admins'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('sub.admins');
        Route::get('list', [AdminController::class, 'list'])->name('sub.admins.list');
        Route::post('store', [AdminController::class, 'store'])->name('sub.admins.store');
        Route::post('status', [AdminController::class, 'status'])->name('sub.admins.status');
    });

    Route::get('profile', [SettingsController::class, 'profile'])->name('profile');
    Route::post('profile/change', [SettingsController::class, 'changeprofile'])->name('profile.edit');
    Route::post('profile/password', [SettingsController::class, 'changepassword'])->name('password.edit');
    Route::post('profile/password/reset', [SettingsController::class, 'resetpassword'])->name('password.reset');

    Route::get('general-settings', [SettingsController::class, 'gsettings'])->name('general.settings');
    Route::post('general-settings/update', [SettingsController::class, 'gsettingsupdate'])->name('general.settings.edit');

    Route::get('email-settings', [SettingsController::class, 'esettings'])->name('email.settings');
    Route::post('email-settings/update', [SettingsController::class, 'esettingsupdate'])->name('email.settings.edit');

    Route::get('payment-settings', [SettingsController::class, 'psettings'])->name('payment.settings');
    Route::post('payment-settings/update', [SettingsController::class, 'psettingsupdate'])->name('payment.settings.edit');

    Route::get('firebase-settings', [SettingsController::class, 'fsettings'])->name('firebase.settings');
    Route::post('firebase-settings/update', [SettingsController::class, 'fsettingsupdate'])->name('firebase.settings.edit');

    Route::get('twilio-settings', [SettingsController::class, 'tsettings'])->name('twilio.settings');
    Route::post('twilio-settings/update', [SettingsController::class, 'tsettingsupdate'])->name('twilio.settings.edit');

    Route::get('privacy-policy', [SettingsController::class, 'privacy_policy'])->name('privacy.policy');
    Route::get('terms-conditions', [SettingsController::class, 'terms_conditions'])->name('terms.conditions');
    Route::get('report-spam', [SettingsController::class, 'report_spam'])->name('report.spam');
    Route::post('cmsupdate/{type}', [SettingsController::class, 'updatecms'])->name('cms.edit');

    Route::group(['prefix' => 'plans'], function () {
        Route::get('/', [PlansController::class, 'index'])->name('plans');
        Route::get('list', [PlansController::class, 'list'])->name('plans.list');
        Route::post('store', [PlansController::class, 'store'])->name('plans.store');
    });

    Route::get('logout', [AdminController::class, 'logout'])->name('logout');


    Route::resource('proofs', IdProofsController::class);
    Route::post('proofsstatus', [IdProofsController::class, 'status'])->name('proof.status');

    // Location Master
    Route::resource('states', StatesController::class);
    Route::post('statestatus', [StatesController::class, 'status'])->name('states.status');
    Route::resource('cities', CitiesController::class);
    Route::post('citystatus', [CitiesController::class, 'status'])->name('cities.status');
    Route::resource('areas', AreasController::class);
    Route::post('areastatus', [AreasController::class, 'status'])->name('areas.status');
    Route::post('areatype', [AreasController::class, 'changetype'])->name('areas.type');
    Route::post('getcities', [AreasController::class, 'getcities'])->name('get.cities');

    Route::resource('faqs', FAQController::class);
    Route::post('faqstatus', [FAQController::class, 'status'])->name('faqs.status');
    Route::resource('education', EducationController::class);
    Route::post('educationstatus', [EducationController::class, 'status'])->name('education.status');
    Route::resource('availabilities', AvailabilitiesController::class);
    Route::post('availabilitystatus', [AvailabilitiesController::class, 'status'])->name('availabilities.status');
    Route::resource('payment-types', PaymentTypesController::class);
    Route::post('paymentstatus', [PaymentTypesController::class, 'status'])->name('payment-types.status');
    Route::resource('industry-types', IndustryTypesController::class);
    Route::post('industrystatus', [IndustryTypesController::class, 'status'])->name('industry-types.status');
    Route::resource('categories', CategoriesController::class);
    Route::post('categorystatus', [CategoriesController::class, 'status'])->name('categories.status');
    Route::resource('skills', SkillsController::class);
    Route::post('skillstatus', [SkillsController::class, 'status'])->name('skills.status');
    Route::post('skilltype', [SkillsController::class, 'changetype'])->name('skills.type');
    Route::resource('advertisings', AdvertisingsController::class);
    Route::post('addstatus', [AdvertisingsController::class, 'status'])->name('advertisings.status');
    Route::resource('notes', NotesController::class);

    Route::resource('news-feeds', NewsFeedsController::class);
    Route::post('newsstatus', [NewsFeedsController::class, 'status'])->name('news-feeds.status');

    Route::resource('job-providers', JobProvidersController::class);
    Route::post('providersstatus', [JobProvidersController::class, 'status'])->name('job-proividers.status');

    Route::resource('job-seekers', JobSeekersController::class);
    Route::post('seekerstatus', [JobSeekersController::class, 'status'])->name('job-seekers.status');
    Route::get('/resume-{rname}', [JobSeekersController::class,'dresume']);

    Route::resource('transactions', TransactionsController::class);
    Route::post('manage-coins', [TransactionsController::class, 'manage_coins']);

    Route::resource('notify-users', NottifyUsersController::class);

    Route::resource('jobs', JobsController::class);
    Route::get('female-security', [JobsController::class, 'female_security'])->name('female-security.index');
    Route::post('jobstatus', [JobsController::class, 'status'])->name('jobs.status');

    Route::resource('feedbacks', FeedbacksController::class);
    Route::post('feedbackstatus', [FeedbacksController::class, 'status'])->name('feedbacks.status');

    Route::resource('spam-requests', SpamRequestsController::class);

    // Route::resource('job-types', JobTypesController::class);
    // Route::resource('salary-types', SalaryTypesController::class);
});
