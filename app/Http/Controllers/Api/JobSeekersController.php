<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobApplications;
use App\Models\User;

class JobSeekersController extends Controller
{
    public function candidate_details(Request $request)
    {
        if (!in_array(auth('sanctum')->user()->type, [3, 4])) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        try {
            $data = User::with([
                'other_info.education', 'other_info.availability', 'current_working', 'skills.skill', 'location.cities', 'location.states', 'location.areas',
                'seeker_feedbacks' => function ($query) {
                    $query->take(2);
                },
                'seeker_feedbacks.provider_info' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
                ->select('id', 'type', 'name', 'email', 'mobile', 'about', 'image')->where('id', $request->user_id)->typeseeker()->available()->first();
            if (!$data) {
                return response()->json(['status' => 0, 'message' => 'Invalid User']);
            }
            $candidate_details = [
                'data' => $data,
                'avg_feedbacks' => $data->FeedbacksAvg(),
            ];
            $proposal = [];
            if ($request->filled('proposal_id')) {
                $request->validate([
                    'proposal_id' => 'exists:job_applications,id',
                ]);
                $proposal = JobApplications::where('id', $request->proposal_id)->select('*')->AppliedOnWithTime()->first();
            }

            $is_proposal_applicable = optional(JobApplications::where('provider_id', auth('sanctum')->user()->id)->where('user_id', $request->user_id)->where('job_id', null)->orderByDesc('id')->first())->status == 3;

            return response()->json(['status' => 1, 'message' => 'Success', 'is_proposal_applicable' => $is_proposal_applicable, 'candidate_details' => $candidate_details, 'proposal' => $proposal], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function work_experience_list(Request $request)
    {
        try {
            $checkuser = User::with('work_experience')->where('id', $request->id)->typeseeker()->available()->first();
            if (!$request->filled('id') || empty($checkuser)) {
                return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
            }
            return response()->json(['status' => 1, 'message' => 'Success', 'data' => $checkuser->work_experience], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
