<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplications;
use App\Models\JobCategories;
use App\Models\Jobs;
use App\Models\Locations;
use App\Models\JobSkills;
use App\Models\OtherNotes;
use App\Models\SpamRequests;
use App\Models\SpamRequestUsers;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobsController extends Controller
{
    public function jobs_list()
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $active_jobs_list = Jobs::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
                'availability',
                'location_info',
                'location_info.states',
                'location_info.cities',
                'location_info.areas',
                'skills.skill'
            ])->select('id', 'user_id', 'title', 'availabilities_id', 'locations_id', 'min_salary', 'max_salary')->where('user_id', auth('sanctum')->user()->id)->where('status', 1)->orderByDesc('created_at')->PostedOn()->get();

            $closed_jobs_list = Jobs::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
                'availability',
                'location_info',
                'location_info.states',
                'location_info.cities',
                'location_info.areas',
                'skills.skill'
            ])->select('id', 'user_id', 'title', 'availabilities_id', 'locations_id', 'min_salary', 'max_salary')->where('user_id', auth('sanctum')->user()->id)->where('status', 2)->orderByDesc('created_at')->PostedOn()->get();

            return response()->json(['status' => 1, 'message' => 'Success', 'active_jobs_list' => $active_jobs_list, 'closed_jobs_list' => $closed_jobs_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function post_job(Request $request)
    {
        $user = auth('sanctum')->user();
        if ($user->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'job_id' => 'required_if:condition,true',
            'title' => 'required',
            'industry_types_id' => 'required|exists:industry_types,id',
            'categories_id' => 'required|exists:categories,id',
            'skills_id' => 'required|exists:skills,id',
            'locations_id' => 'required|exists:locations,id',
            'availabilities_id' => 'required|exists:availabilities,id',
            'payment_types_id' => 'required|exists:payment_types,id',
            'min_salary' => 'required|numeric',
            'max_salary' => 'required|numeric|gt:min_salary',
            'gender' => 'required|in:1,2,3',
            'education_id' => 'required',
            'experience_type' => 'required|in:1,2,3',
            'exp_years' => 'required',
            'candidates' => 'required|numeric|min:1',
        ], [
            'max_salary.gt' => 'The maximum salary must be greater than the minimum salary.',
            '*.exists' => 'Invalid :attribute',
        ]);
        DB::beginTransaction();
        try {
            if ($request->filled('job_id')) {
                $job = Jobs::find($request->job_id);
                if (!$job) {
                    return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
                }
            } else {

                $requiredCoins = settingsdata()->job_post_coins;
                $check_coins = userCoins($user->id) - $requiredCoins;
                if ($check_coins < 0) {
                    return response()->json(['status' => 3, 'message' => 'Insufficient Coins'], 200);
                }
                // Deduct coins
                $deductResult = deductCoins($user->id, $requiredCoins);
                if (!$deductResult) {
                    throw new \Exception('Unable to deduct the coins!');
                }
                // New Entry
                $tr = new Transactions();
                $tr->type = 5;
                $tr->user_id = $user->id;
                $tr->final_coins = $requiredCoins;
                $tr->save();

                $job = new Jobs;
                $job->user_id = auth('sanctum')->user()->id;
            }
            $job->title = $request->title;
            $job->description = $request->description ?? '';
            $job->industry_types_id = $request->industry_types_id;
            $job->locations_id = $request->locations_id;
            $job->availabilities_id = $request->availabilities_id;
            $job->payment_types_id = $request->payment_types_id;
            $job->min_salary = $request->min_salary;
            $job->max_salary = $request->max_salary;
            $job->gender = $request->gender;
            $job->education_id = $request->education_id;
            $job->experience_type = $request->experience_type;
            $job->exp_years = $request->exp_years;
            $job->is_female_required = $request->gender == 2 ? 1 : 2;
            $job->candidates = $request->candidates;
            if ($request->gender == 2) {
                $job->is_female_required = 1;
                $job->status = 5;
            }
            $job->save();
            if ($job->save()) {
                JobCategories::where('job_id', $job->id)->delete();
                foreach (explode(',', $request->categories_id) as $category) {
                    $cat = new JobCategories();
                    $cat->job_id = $job->id;
                    $cat->categories_id = $category;
                    $cat->save();
                }
                JobSkills::where('job_id', $job->id)->delete();
                foreach (explode(',', $request->skills_id) as $skill) {
                    $cat = new JobSkills();
                    $cat->job_id = $job->id;
                    $cat->skills_id = $skill;
                    $cat->save();
                }
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return errorResponse($th->getMessage());
        }
    }
    public function close_job(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $job = Jobs::where('id', $request->id)->where('user_id', auth('sanctum')->user()->id)->first();
            if (!$job) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
            }
            $current_date = Carbon::now();
            $job->status = 2;
            $job->closed_at = $current_date;
            $job->save();
            return response()->json(['status' => 1, 'message' => 'success']);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_job(Request $request)
    {
        try {
            $job = Jobs::with([
                'user' => function ($query) {
                    $query->select('id', 'image');
                },
                'industry_type_info',
                'categories.category',
                'skills.skill' => function ($query) {
                    $query->select('id', 'categories_id', 'title_en', 'title_hi', 'title_gj');
                },
                'location_info.states' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'location_info.cities' => function ($query) {
                    $query->select('id', 'state_id', 'title_en', 'title_hi', 'title_gj');
                },
                'location_info.areas' => function ($query) {
                    $query->select('id', 'state_id', 'city_id', 'title_en', 'title_hi', 'title_gj');
                },
                'availability' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'education_info' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                }
            ])->where('id', $request->id)->select('*')->PostedOnWithTime()->ClosedOn()->first();
            if (!$job) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
            }
            $job->makeHidden(['is_female_required', 'closed_at', 'is_reposted', 'reposted_on', 'status']);

            $is_proposal_applicable = optional(JobApplications::where('provider_id', $job->user_id)->where('user_id', $request->user_id)->where('job_id', $job->id)->orderByDesc('id')->first())->status == 3;

            return response()->json(['status' => 1, 'message' => 'success', 'is_proposal_applicable' => $is_proposal_applicable, 'job_details' => $job]);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function spam_job(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $request->validate([
                'job_id' => 'required|exists:jobs,id',
                'description' => 'required',
            ]);
            $checkjob = Jobs::where('id', $request->job_id)->where('status', 1)->first();
            if (!$checkjob) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job'], 200);
            }
            $checkexist = SpamRequests::where('job_id', $request->job_id)->first();
            if ($request->filled('notes_id')) {
                $checknote = OtherNotes::where('id', $request->notes_id)->where('type', 2)->first();
                $note = !empty($checknote) ? $checknote->title_en : '';
                $notes_id = !empty($checknote) ? $checknote->id : '';
            }
            if (!empty($checkexist)) {
                $check_exist_user = SpamRequestUsers::where('spam_request_id', $checkexist->id)->where('user_id', auth('sanctum')->user()->id)->first();
                if (!empty($check_exist_user)) {
                    return response()->json(['status' => 4, 'message' => 'Spam request already created'], 200);
                }
                $sru = new SpamRequestUsers();
                $sru->spam_request_id = $checkexist->id;
                $sru->user_id = auth('sanctum')->user()->id;
                $sru->notes_id = $notes_id;
                $sru->note = $note;
                $sru->description = $request->description;
                $sru->save();
            } else {
                $sr = new SpamRequests();
                $sr->job_id = $request->job_id;
                $sr->save();
                $sru = new SpamRequestUsers();
                $sru->spam_request_id = $sr->id;
                $sru->user_id = auth('sanctum')->user()->id;
                $sru->notes_id = $notes_id;
                $sru->note = $note;
                $sru->description = $request->description;
                $sru->save();
            }
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
