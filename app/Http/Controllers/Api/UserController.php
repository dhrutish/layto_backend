<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jobs;
use App\Models\Locations;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    function refer_earn_history(Request $request)
    {
        $data = Transactions::with(['user' => function ($query) {
            $query->select('id', 'name', 'image');
        }])->where('refer_user_id', auth('sanctum')->user()->id)->where('type', 9)->select('id', 'user_id', 'final_coins', 'user_id');
        if ($request->filled('limit')) {
            $data = $data->take($request->limit);
        }
        $data = $data->latest()->get();
        return response()->json(['status' => 1, 'message' => 'Success', 'referral_coins' => settingsdata()->referral_coins, 'referral_code' => auth('sanctum')->user()->referral_code, 'data' => $data], 200);
    }
    public function user_locations()
    {
        try {
            $user_locations = Locations::with([
                'states' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                }, 'cities' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                }, 'areas' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                }
            ])->where('user_id', auth('sanctum')->user()->id)->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'user_locations' => $user_locations], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function provider_details(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $job = Jobs::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'mobile', 'about', 'image');
                },
                'user.provider_feedbacks' => function ($query) {
                    $query->select('id', 'provider_id', 'user_id', 'rating', 'comment');
                },
                'user.provider_feedbacks.seeker_info' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
                'location_info',
                'location_info.cities',
                'location_info.states',
                'user.avljobs' => function ($query) {
                    $query->select('id', 'user_id', 'locations_id', 'payment_types_id', 'availabilities_id', 'title', 'min_salary', 'max_salary')->PostedOn();
                },
                'user.avljobs.user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
                'user.avljobs.skills.skill' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'user.avljobs.availability' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'user.avljobs.location_info' => function ($query) {
                    $query->select('id', 'user_id', 'city_id', 'area_id');
                },
                'user.avljobs.location_info.areas' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'user.avljobs.location_info.cities' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
            ])
                ->select('id', 'user_id', 'locations_id', DB::raw('(SELECT ROUND(AVG(rating), 1) FROM feedbacks WHERE provider_id = jobs.user_id) AS provider_feedbacks_avg'))
                ->where('id', $request->id)
                ->first();

            if (!$job) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
            }
            return response()->json(['status' => 1, 'message' => 'Success', 'company_info' => $job], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
