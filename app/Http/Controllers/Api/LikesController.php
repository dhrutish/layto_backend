<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorites;
use App\Models\Jobs;
use App\Models\User;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    function like_candidate(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            "user_id" => 'required|exists:users,id',
        ]);
        try {
            $checkuser = User::where('id', $request->user_id)->typeseeker()->available()->first();
            if (empty($checkuser)) {
                return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
            }
            $check_exist = Favorites::where('provider_id', auth('sanctum')->user()->id)->where('user_id', $request->user_id)->first();
            if (!empty($check_exist)) {
                return response()->json(['status' => 0, 'message' => 'The Candidate has already been liked.'], 200);
            }
            $fav = new Favorites();
            $fav->provider_id = auth('sanctum')->user()->id;
            $fav->user_id = $request->user_id;
            $fav->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function unlike_candidate(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            "user_id" => 'required|exists:users,id',
        ]);
        try {
            $check_exist = Favorites::where('provider_id', auth('sanctum')->user()->id)->where('user_id', $request->user_id)->first();
            if (!empty($check_exist)) {
                $check_exist->delete();
                return response()->json(['status' => 1, 'message' => 'Success'], 200);
            }
            return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function like_job(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            "job_id" => 'required|exists:jobs,id',
        ]);
        try {
            $checkjob = Jobs::where('id', $request->job_id)->where('status', 1)->first();
            if (empty($checkjob)) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job'], 200);
            }
            $check_exist = Favorites::where('user_id', auth('sanctum')->user()->id)->where('job_id', $request->job_id)->first();
            if (!empty($check_exist)) {
                return response()->json(['status' => 0, 'message' => 'The job has already been liked.'], 200);
            }
            $fav = new Favorites();
            $fav->user_id = auth('sanctum')->user()->id;
            $fav->job_id = $request->job_id;
            $fav->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function unlike_job(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            "job_id" => 'required|exists:jobs,id',
        ]);
        try {
            $check_exist = Favorites::where('user_id', auth('sanctum')->user()->id)->where('job_id', $request->job_id)->first();
            if (!empty($check_exist)) {
                $check_exist->delete();
                return response()->json(['status' => 1, 'message' => 'Success'], 200);
            }
            return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
