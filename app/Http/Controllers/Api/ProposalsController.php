<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplications;
use App\Models\Jobs;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalsController extends Controller
{
    public function manage_proposal(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:job_applications,id',
            'status' => 'required|in:2,3'
        ]);
        DB::beginTransaction();
        try {
            $cja = JobApplications::where('id', $request->proposal_id)->first();
            if (empty($cja)) {
                return response()->json(['status' => 0, 'message' => "Invalid Job Proposal"], 200);
            }
            $cja->status = $request->status;
            $cja->save();

            $provider = $cja->provider_info;
            if($request->status == 2){
                $title = $provider->name;
                $description = 'Your proposal has been rejected';
            }
            $checkseeker = $cja->seeker;
            store_notification($checkseeker->id, $job_id = null, $provider->id, $title, $description, $request->status == 2 ? 5 : 6);
            send_notification([$checkseeker->fcm_token], $title, $description);
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Success"], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
    public function seeker_proposal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        DB::beginTransaction();
        try {
            $provider = auth('sanctum')->user();
            if ($provider->type != 3) {
                return response()->json(['status' => 0, 'message' => "Invalid User"], 200);
            }
            $checkseeker = User::where('id', $request->user_id)->typeseeker()->available()->first();
            if (!$checkseeker) {
                return response()->json(['status' => 0, 'message' => "Invalid Job seeker"], 200);
            }
            $ja = JobApplications::where('provider_id', $provider->id)->where('user_id', $request->user_id)->where('status','!=',3)->where('job_id', null)->first();
            if (!empty($ja)) {
                return response()->json(['status' => 0, 'message' => "The proposal has already been requested"], 200);
            }

            $requiredCoins = settingsdata()->seeker_connect_coins;
            $check_coins = userCoins($provider->id) - $requiredCoins;
            if ($check_coins < 0) {
                return response()->json(['status' => 3, 'message' => 'Insufficient Coins'], 200);
            }
            // Deduct coins
            $deductResult = deductCoins($provider->id, $requiredCoins);
            if (!$deductResult) {
                throw new \Exception('Unable to deduct the coins!');
            }
            // New Entry
            $tr = new Transactions();
            $tr->type = 10;
            $tr->user_id = $provider->id;
            $tr->final_coins = $requiredCoins;
            $tr->save();


            $ja = new JobApplications();
            $ja->provider_id = $provider->id;
            $ja->user_id = $request->user_id;
            $ja->save();

            $title = 'New Proposal';
            $description = $provider->name . ' wants to connect with you';
            store_notification($checkseeker->id, $job_id = null, $provider->id, $title, $description, 4);
            send_notification([$checkseeker->fcm_token], $title, $description);

            DB::commit();
            return response()->json(['status' => 1, 'message' => "Success"], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
    public function job_proposal(Request $request)
    {
        // Apply for a JOb
        $request->validate([
            'job_id' => 'required|exists:jobs,id'
        ]);
        DB::beginTransaction();
        try {
            $user = auth('sanctum')->user();
            if ($user->type != 4) {
                return response()->json(['status' => 0, 'message' => "Invalid User"], 200);
            }
            $checkjob = Jobs::where('id', $request->job_id)->where('status', 1)->first();
            if (!$checkjob) {
                return response()->json(['status' => 0, 'message' => "Invalid Job"], 200);
            }
            $ja = JobApplications::where('provider_id', $checkjob->user_id)->where('user_id', auth('sanctum')->user()->id)->where('job_id', $checkjob->id)->where('status','!=',3)->first();
            if (!empty($ja)) {
                return response()->json(['status' => 0, 'message' => "The Job proposal has already been applied"], 200);
            }

            $requiredCoins = settingsdata()->apply_job_coins;
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
            $tr->type = 12;
            $tr->user_id = $user->id;
            $tr->final_coins = $requiredCoins;
            $tr->save();


            $ja = new JobApplications();
            $ja->provider_id = $checkjob->user_id;
            $ja->job_id = $checkjob->id;
            $ja->user_id = auth('sanctum')->user()->id;
            $ja->from_amount = $request->from_amount ?? 0;
            $ja->to_amount = $request->to_amount ?? 0;
            $ja->description = $request->description ?? '';
            $ja->save();
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Success"], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }



    public function pending_proposal(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'job_id' => 'required'
        ]);
        try {
            $job = Jobs::where('id', $request->job_id)->where('user_id', auth('sanctum')->user()->id)->first();
            if (!$job) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
            }
            $proposals_list = JobApplications::with([
                'provider_info' => function ($query) {
                    $query->select('id', 'name');
                },
                'seeker' => function ($query) {
                    $query->select('id', 'name');
                },
                'seeker.skills.skill' => function ($query) {
                    $query->select('id', 'categories_id', 'title_en', 'title_hi', 'title_gj');
                },
            ])->where('provider_id', auth('sanctum')->user()->id)->where('job_id', $job->id)->select('*')->AppliedOn();

            $pending_proposals = $proposals_list->where('status', 1)->get();
            $pending_proposals->each(function ($proposal) {
                if ($proposal->provider_info) {
                    $proposal->provider_info->makeHidden('image_url');
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'pending_proposals_count' => $pending_proposals->count(), 'pending_proposals' => $pending_proposals], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function accept_proposals(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'job_id' => 'required'
        ]);
        try {
            $job = Jobs::where('id', $request->job_id)->where('user_id', auth('sanctum')->user()->id)->first();
            if (!$job) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
            }
            $proposals_list = JobApplications::with([
                'provider_info' => function ($query) {
                    $query->select('id', 'name');
                },
                'seeker' => function ($query) {
                    $query->select('id', 'name');
                },
                'seeker.skills.skill' => function ($query) {
                    $query->select('id', 'categories_id', 'title_en', 'title_hi', 'title_gj');
                },
            ])->where('provider_id', auth('sanctum')->user()->id)->where('job_id', $job->id)->select('*')->AppliedOn();

            $accept_proposals = $proposals_list->where('status', 2)->get();
            $accept_proposals->each(function ($proposal) {
                if ($proposal->provider_info) {
                    $proposal->provider_info->makeHidden('image_url');
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'accept_proposals_count' => $accept_proposals->count(), 'accept_proposals' => $accept_proposals], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function reject_proposals(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'job_id' => 'required'
        ]);
        try {
            $job = Jobs::where('id', $request->job_id)->where('user_id', auth('sanctum')->user()->id)->first();
            if (!$job) {
                return response()->json(['status' => 0, 'message' => 'Invalid Job Id']);
            }
            $proposals_list = JobApplications::with([
                'provider_info' => function ($query) {
                    $query->select('id', 'name');
                },
                'seeker' => function ($query) {
                    $query->select('id', 'name');
                },
                'seeker.skills.skill' => function ($query) {
                    $query->select('id', 'categories_id', 'title_en', 'title_hi', 'title_gj');
                },
            ])->where('provider_id', auth('sanctum')->user()->id)->where('job_id', $job->id)->select('*')->AppliedOn();
            $reject_proposals = $proposals_list->where('status', 3)->get();
            $reject_proposals->each(function ($proposal) {
                if ($proposal->provider_info) {
                    $proposal->provider_info->makeHidden('image_url');
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'reject_proposals_count' => $reject_proposals->count(), 'reject_proposals' => $reject_proposals], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }



    public function applied_jobs(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $jobs_list = JobApplications::with([
                'job_info' => function ($query) {
                    $query->select('id', 'availabilities_id', 'locations_id', 'title', 'min_salary', 'max_salary')->PostedOn();
                },
                'job_info.availability',
                'job_info.skills.skill',
                'job_info.location_info' => function ($query) {
                    $query->select('id', 'state_id', 'city_id', 'area_id');
                },
                'job_info.location_info.areas',
                'job_info.location_info.cities',
                'job_info.location_info.states',
                'provider_info' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])->where('user_id', auth('sanctum')->user()->id)->where('job_id', '!=', '')->ActiveJob()->get()->makeHidden(['status', 'from_amount', 'to_amount']);
            $applied_jobs = $jobs_list->each(function ($data) {
                if ($data->job_info) {
                    $data->job_info->makeHidden('IsJobEditable');
                }
                if ($data->job_info) {
                    $data->job_info->availability->makeHidden('is_available');
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'applied_jobs_count' => $applied_jobs->count(), 'applied_jobs' => $applied_jobs], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function accepted_jobs(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $jobs_list = JobApplications::with([
                'job_info' => function ($query) {
                    $query->select('id', 'availabilities_id', 'locations_id', 'title', 'min_salary', 'max_salary')->PostedOn();
                },
                'job_info.availability',
                'job_info.skills.skill',
                'job_info.location_info' => function ($query) {
                    $query->select('id', 'state_id', 'city_id', 'area_id');
                },
                'job_info.location_info.areas',
                'job_info.location_info.cities',
                'job_info.location_info.states',
                'provider_info' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])->where('user_id', auth('sanctum')->user()->id)->where('job_id', '!=', '')->where('status', 2)->get()->makeHidden(['status', 'from_amount', 'to_amount']);
            $accepted_jobs = $jobs_list->each(function ($data) {
                if ($data->job_info) {
                    $data->job_info->makeHidden('IsJobEditable');
                    if ($data->job_info->availability) {
                        $data->job_info->availability->makeHidden('is_available');
                    }
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'accepted_jobs_count' => $accepted_jobs->count(), 'accepted_jobs' => $accepted_jobs], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function closed_jobs(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $jobs_list = JobApplications::with([
                'job_info' => function ($query) {
                    $query->select('id', 'availabilities_id', 'locations_id', 'title', 'min_salary', 'max_salary')->PostedOn();
                },
                'job_info.availability',
                'job_info.skills.skill',
                'job_info.location_info' => function ($query) {
                    $query->select('id', 'state_id', 'city_id', 'area_id');
                },
                'job_info.location_info.areas',
                'job_info.location_info.cities',
                'job_info.location_info.states',
                'provider_info' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
            ])->where('user_id', auth('sanctum')->user()->id)->where('job_id', '!=', '')->ClosedJob()->get()->makeHidden(['status', 'from_amount', 'to_amount']);
            $closed_jobs = $jobs_list->each(function ($data) {
                if ($data->job_info) {
                    $data->job_info->makeHidden('IsJobEditable');
                }
                if ($data->job_info) {
                    $data->job_info->availability->makeHidden('is_available');
                }
            });
            return response()->json(['status' => 1, 'message' => 'Success', 'closed_jobs_count' => $closed_jobs->count(), 'closed_jobs' => $closed_jobs], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
