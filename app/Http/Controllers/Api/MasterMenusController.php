<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Areas;
use App\Models\Availabilities;
use App\Models\Categories;
use App\Models\Cities;
use App\Models\Education;
use App\Models\FAQ;
use App\Models\IndustryTypes;
use App\Models\Notifications;
use App\Models\OtherNotes;
use App\Models\PaymentTypes;
use App\Models\Skills;
use App\Models\States;
use Illuminate\Http\Request;

class MasterMenusController extends Controller
{
    function education_list()
    {
        try {
            $education_list = Education::get();
            return response()->json(['status' => 1, 'message' => 'Success', 'education_list' => $education_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function payment_type_list()
    {
        try {
            $payment_type_list = PaymentTypes::available()->get()->makeHidden('is_available');
            return response()->json(['status' => 1, 'message' => 'Success', 'payment_type_list' => $payment_type_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function availabilities_list()
    {
        try {
            $availabilities_list = Availabilities::available()->get()->makeHidden('is_available');
            return response()->json(['status' => 1, 'message' => 'Success', 'availabilities_list' => $availabilities_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }


    public function notes(String $type)
    {
        try {
            if (!in_array($type, [1, 2])) {
                return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
            }
            $notes = OtherNotes::where('type', $type)->get()->makeHidden(['type']);
            return response()->json(['status' => 1, 'message' => 'Success', 'notes' => $notes], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function industry_types(Request $request)
    {
        try {
            $data = IndustryTypes::available()->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'industries_list' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function categories(Request $request)
    {
        $request->validate([
            'industry_types_id' => 'required',
        ]);
        try {
            $data = Categories::where('industry_types_id', $request->industry_types_id)->available()->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'categories' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function skills(Request $request)
    {
        $request->validate([
            'categories_id' => 'required',
        ]);
        try {
            $data = Skills::whereIn('categories_id', explode(',', $request->categories_id))->default()->available()->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'skills' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function states(Request $request)
    {
        try {
            $data = States::available()->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'states' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function cities(Request $request)
    {
        try {
            $data = Cities::available();
            if ($request->filled('state_id')) {
                $data = $data->where('state_id', $request->state_id);
            }
            $data = $data->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'cities' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function areas(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'city_id' => 'required',
        ]);
        try {
            $data = Areas::where('state_id', $request->state_id)->where('city_id', $request->city_id)->available()->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'areas' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function faqs_list(Request $request)
    {
        try {
            $faqs_list = FAQ::available()->get()->makeHidden('is_available');
            return response()->json(['status' => 1, 'message' => 'success', 'faqs_list' => $faqs_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function notifications()
    {
        try {
            $data = Notifications::with([
                'provider' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
                'job' => function ($query) {
                    $query->select('id', 'user_id', 'title');
                },
                'job.user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])->where('user_id', auth('sanctum')->user()->id)->latest()->get()->each(function ($noti) {
                if (!empty($noti->job)) {
                    $noti->job->makeHidden('IsJobEditable');
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
