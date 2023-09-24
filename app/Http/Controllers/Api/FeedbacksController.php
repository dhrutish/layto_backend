<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedbacks;
use App\Models\Jobs;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbacksController extends Controller
{
    function provider_feedbacks_list(Request $request)
    {
        try {
            $checkuser = User::where('id', $request->id)->typeprovider()->available()->first();
            if (!$request->filled('id') || empty($checkuser)) {
                return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
            }
            $checkuser->location->makeHidden(['area_id', 'city_id', 'state_id']);
            $provider = [
                'name' => $checkuser->name,
                'image_url' => $checkuser->image_url,
                'avg_feedbacks' => $checkuser->provider_feedbacks_avg,
                'location' => [
                    'title' => $checkuser->location->title,
                    'address' => $checkuser->location->address,
                    'pincode' => $checkuser->location->pincode,
                    'url' => $checkuser->location->url,
                    'states' => $checkuser->location->states,
                    'cities' => $checkuser->location->cities,
                    'areas' => $checkuser->location->areas,
                ],
            ];
            $data = Feedbacks::with(['seeker_info' => function ($query) {
                $query->select('id', 'name', 'image');
            }])->where('provider_id', $checkuser->id)->where('type', 2)->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'provider' => $provider, 'data' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function seeker_feedbacks_list(Request $request)
    {
        try {
            $checkuser = User::where('id', $request->id)->typeseeker()->available()->first();
            if (!$request->filled('id') || empty($checkuser)) {
                return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
            }
            $seeker = [
                'name' => $checkuser->name,
                'image_url' => $checkuser->image_url,
                'avg_feedbacks' => $checkuser->seeker_feedbacks_avg,
                'current_working' => $checkuser->current_working
            ];
            $data = Feedbacks::with(['provider_info' => function ($query) {
                $query->select('id', 'name', 'image');
            }])->where('user_id', $checkuser->id)->where('type', 1)->latest()->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'seeker' => $seeker, 'data' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function raise_dispute(Request $request)
    {
        // type = 1 = dispute raised by Job Provider
        // type = 2 = dispute raised by Job Seeker
        if (!in_array($request->type, [1, 2])) {
            return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
        }
        $request->validate([
            "feedback_id" => 'required|exists:feedbacks,id',
            "dispute_description" => 'required',
        ]);
        try {
            $checkfedback = Feedbacks::where('id', $request->feedback_id)->where('type', $request->type == 1 ? 2 : 1)->where('is_dispute_created', 2)->first();
            if (empty($checkfedback)) {
                return response()->json(['status' => 0, 'message' => 'Dispute already raised'], 200);
            }
            $checkfedback->is_dispute_created = 1;
            $checkfedback->dispute_description = $request->dispute_description;
            $checkfedback->dispute_status = 1;
            $checkfedback->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function candidate_feedback(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'user_id' => 'required',
            'job_id' => 'required',
            'rating' => 'required|numeric|validate_rating',
        ]);
        $job = Jobs::find($request->job_id);
        if (!$job) {
            return response()->json(['status' => 0, 'message' => 'Invalid Job ID'], 200);
        }
        try {
            $candidateFeedback = new Feedbacks();
            $candidateFeedback->provider_id = $job->user_id;
            $candidateFeedback->user_id = $request->user_id;
            $candidateFeedback->job_id = $request->job_id;
            $candidateFeedback->rating = $request->rating;
            $candidateFeedback->comment = $request->comment ?? '';
            $candidateFeedback->type = 1;
            $candidateFeedback->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    function job_feedback(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'provider_id' => 'required|exists:users,id,type,3',
            'user_id' => 'required',
            'rating' => 'required|numeric|in:1,2,3,4,5',
        ], [
            'provider_id.required' => 'The provider ID is required.',
            'provider_id.exists' => 'The selected provider ID is invalid.',
        ]);
        $provider = User::where('id', $request->provider_id)->where('type', 3)->first();
        try {
            $candidateFeedback = new Feedbacks();
            $candidateFeedback->provider_id = $provider->id;
            $candidateFeedback->user_id = $request->user_id;
            $candidateFeedback->rating = $request->rating;
            $candidateFeedback->comment = $request->comment ?? '';
            $candidateFeedback->type = 2;
            $candidateFeedback->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
