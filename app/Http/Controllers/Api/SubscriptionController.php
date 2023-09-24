<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\OtherNotes;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function subscriptions_list(Request $request)
    {
        try {
            $notes = OtherNotes::where('type', 1)->get();
            $subscriptions_list = Plans::orderBy('from_coins')->get();
            return response()->json(['status' => 1, 'message' => 'Success', 'total_available_coins' => userCoins(auth('sanctum')->user()->id), 'notes' => $notes, 'subscriptions_list' => $subscriptions_list, 'is_gst_included' => settingsdata()->is_gst_included, 'gst' => settingsdata()->gst], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function buy_coins(Request $request)
    {
        $request->validate([
            'plan_id' => 'required',
            'plan_from_coins' => 'required',
            'plan_to_coins' => 'required',
            'plan_additionals_coins_pr' => 'required',
            'purchase_coins' => 'required',
            'final_coins' => 'required',
            'amount' => 'required',
            'transaction_id' => 'required',
        ]);
        if (empty(Plans::find($request->plan_id))) {
            return response()->json(['status' => 0, 'message' => 'Invalid Plan ID'], 200);
        }
        DB::beginTransaction();
        try {
            $tr = new Transactions;
            $tr->type = 6;
            $tr->user_id = auth('sanctum')->user()->id;
            $tr->plan_id = $request->plan_id;
            $tr->plan_from_coins = $request->plan_from_coins;
            $tr->plan_to_coins = $request->plan_to_coins;
            $tr->plan_additional_coins_pr = $request->plan_additonal_coins_pr;
            $tr->purchase_coins = $request->purchase_coins;
            $tr->final_coins = $request->final_coins;
            $tr->amount = $request->amount;
            $tr->transaction_id = $request->transaction_id;
            $tr->coin_expire_days = settingsdata()->coin_expire_days;
            $tr->save();
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
    function coins_history(Request $request)
    {
        $data = Transactions::with(['job.user' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where('user_id', auth('sanctum')->user()->id)
            ->select('id', 'type', 'user_id', 'job_id', 'type', 'final_coins', 'amount', 'description', 'coin_expire_days', 'created_at')
            ->latest()->get()
            ->makeVisible(['created_at', 'coin_expire_days'])
            ->each(function ($favorite) {
                if (!is_null($favorite->job)) {
                    $favorite->job->makehidden(["industry_types_id", "locations_id", "payment_types_id", "education_id", "availabilities_id", "title", "description", "min_salary", "max_salary", "gender", "candidates", "experience_type", "exp_years", "is_female_required", "is_reposted", "reposted_on", "status", "closed_at", "IsJobEditable"]);
                    $favorite->job->user->makehidden(["image_url"]);
                }
            });
        return response()->json(['status' => 1, 'message' => 'Success', 'total_available_coins' => userCoins(auth('sanctum')->user()->id), 'data' => $data], 200);
    }
}
