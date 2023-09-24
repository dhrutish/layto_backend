<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedbacks;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jobs;
use App\Models\SpamRequestUsers;
use App\Models\Transactions;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Chart filters :- Last 7 days, Last Three Months, Last Six Months, This Year

        // $currentTime = Carbon::now();
        // $currentTime->addDays(30);
        // $oneYearAgo = Carbon::now()->subMonths(9);
        // for ($i = 0; $i < 100; $i++) {
        //     $randomDatetime = Carbon::createFromTimestamp(
        //         rand($oneYearAgo->timestamp, $currentTime->timestamp)
        //     );
        //     $randomRow = User::whereIn('type',[3,4])->inRandomOrder()->first();
        //     $randomRow->created_at = $randomDatetime;
        //     $randomRow->save();

        //     $te111 = Carbon::createFromTimestamp(
        //         rand($oneYearAgo->timestamp, $currentTime->timestamp)
        //     );
        //     $randomRow1 = Transactions::inRandomOrder()->first();
        //     $randomRow1->created_at = $te111;
        //     $randomRow1->save();

        //     $randomDatetime22 = Carbon::createFromTimestamp(
        //         rand($oneYearAgo->timestamp, $currentTime->timestamp)
        //     );
        //     $randomRow2 = UserOtp::inRandomOrder()->first();
        //     $randomRow2->created_at = $randomDatetime22;
        //     $randomRow2->save();

        //     $randomDatetime11 = Carbon::createFromTimestamp(
        //         rand($oneYearAgo->timestamp, $currentTime->timestamp)
        //     );
        //     $randomRow3 = Jobs::inRandomOrder()->first();
        //     $randomRow3->created_at = $randomDatetime11;
        //     $randomRow3->save();
        // }

        $total_providers = User::typeprovider()->available()->count();
        $total_seekers = User::typeseeker()->available()->count();
        $total_spam_requests = SpamRequestUsers::count();
        $total_jobs = Jobs::count();
        $total_avail_jobs = Jobs::available()->count();
        $total_closed_jobs = Jobs::closed()->count();
        $total_fsp_jobs = Jobs::fsp()->count();
        $total_disputes = Feedbacks::where('is_dispute_created', 1)->where('dispute_status',1)->count();

        if ($request->ajax()) {

            $created_at_to_year = DB::raw("YEAR(created_at) as year");
            $created_at_to_mname = DB::raw("MONTHNAME(created_at) as labels");
            $created_at_to_dmy = DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as labels');
            $gby_mname = DB::raw("MONTHNAME(created_at)");
            $gby_dmy = DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y")');
            $sum_of_amount = DB::raw("SUM(amount) as data");
            $cnt_of_id = DB::raw("COUNT(id) as data");
            $sub_days_7 = now()->subDays(7);
            $sub_months_3 = Carbon::now()->subMonths(3);
            $sub_months_6 = Carbon::now()->subMonths(6);

            $userlabels = User::whereIn('type', [3, 4])->orderBy('created_at');;
            if ($request->er_filter == 1) {
                $userlabels = $userlabels->whereYear('created_at', date('Y'))->select($created_at_to_mname)->groupBy($gby_mname);
            }
            if ($request->er_filter == 7) {
                $userlabels = $userlabels->whereDate('created_at', '>=', $sub_days_7)->whereDate('created_at','<=',date('Y-m-d'))->select($created_at_to_dmy)->groupBy($gby_dmy);
            }
            if ($request->er_filter == 3) {
                $userlabels = $userlabels->whereDate('created_at', '>=', $sub_months_3)->select($created_at_to_mname)->groupBy($gby_mname);
            }
            if ($request->er_filter == 6) {
                $userlabels = $userlabels->whereDate('created_at', '>=', $sub_months_6)->select($created_at_to_mname)->groupBy($gby_mname);
            }
            $userlabels = $userlabels->pluck('labels');
            $providers = $seekers = [];
            foreach ($userlabels as $label) {
                $pdata = User::where('type', 3)->whereYear('created_at', date('Y'))->orderBy('created_at');
                $sdata = User::where('type', 4)->whereYear('created_at', date('Y'))->orderBy('created_at');
                if ($request->er_filter == 7) {
                    $pdata = $pdata->whereDate('created_at', date('Y-m-d', strtotime($label)));
                    $sdata = $sdata->whereDate('created_at', date('Y-m-d', strtotime($label)));
                } else {
                    $pdata = $pdata->where(DB::raw("MONTHNAME(created_at)"), $label);
                    $sdata = $sdata->where(DB::raw("MONTHNAME(created_at)"), $label);
                }
                $pdata = $pdata->count();
                $sdata = $sdata->count();
                $providers[] = $pdata;
                $seekers[] = $sdata;
            }

            $earnings = Transactions::where('type',3)->orderBy('created_at');
            $otpdata = UserOtp::orderBy('created_at');
            $jobsdata = Jobs::orderBy('created_at');
            if ($request->er_filter == 1) {
                $earnings = $earnings->whereYear('created_at', date('Y'))->select($created_at_to_year, $created_at_to_mname, $sum_of_amount)->groupBy($gby_mname)->pluck('data', 'labels');
                $otpdata = $otpdata->whereYear('created_at', date('Y'))->select($created_at_to_year, $created_at_to_mname, $cnt_of_id)->groupBy($gby_mname)->pluck('data', 'labels');
                $jobsdata = $jobsdata->whereYear('created_at', date('Y'))->select($created_at_to_year, $created_at_to_mname, $cnt_of_id)->groupBy($gby_mname)->pluck('data', 'labels');
            }
            if ($request->er_filter == 7) {
                $earnings = $earnings->whereDate('created_at', '>=', $sub_days_7)->whereDate('created_at','<=',date('Y-m-d'))->select($created_at_to_year, $created_at_to_dmy, $sum_of_amount)->groupBy($gby_dmy)->get();
                $otpdata = $otpdata->whereDate('created_at', '>=', $sub_days_7)->whereDate('created_at','<=',date('Y-m-d'))->select($created_at_to_year, $created_at_to_dmy, $cnt_of_id)->groupBy($gby_dmy)->get();
                $jobsdata = $jobsdata->whereDate('created_at', '>=', $sub_days_7)->whereDate('created_at','<=',date('Y-m-d'))->select($created_at_to_year, $created_at_to_dmy, $cnt_of_id)->groupBy($gby_dmy)->get();
            }
            if ($request->er_filter == 3) {
                $earnings = $earnings->whereDate('created_at', '>=', $sub_months_3)->select($created_at_to_year, $created_at_to_mname, $sum_of_amount)->groupBy($gby_mname)->pluck('data', 'labels');
                $otpdata = $otpdata->whereDate('created_at', '>=', $sub_months_3)->select($created_at_to_year, $created_at_to_mname, $cnt_of_id)->groupBy($gby_mname)->pluck('data', 'labels');
                $jobsdata = $jobsdata->whereDate('created_at', '>=', $sub_months_3)->select($created_at_to_year, $created_at_to_mname, $cnt_of_id)->groupBy($gby_mname)->pluck('data', 'labels');
            }
            if ($request->er_filter == 6) {
                $earnings = $earnings->whereDate('created_at', '>=', $sub_months_6)->select($created_at_to_year, $created_at_to_mname, $sum_of_amount)->groupBy($gby_mname)->pluck('data', 'labels');
                $otpdata = $otpdata->whereDate('created_at', '>=', $sub_months_6)->select($created_at_to_year, $created_at_to_mname, $cnt_of_id)->groupBy($gby_mname)->pluck('data', 'labels');
                $jobsdata = $jobsdata->whereDate('created_at', '>=', $sub_months_6)->select($created_at_to_year, $created_at_to_mname, $cnt_of_id)->groupBy($gby_mname)->pluck('data', 'labels');
            }
            $earning_labels =  $request->er_filter == 7 ? collect($earnings)->pluck('labels') : $earnings->keys();
            $earning_data = array_map(fn ($value) => $value ?? 0, $request->er_filter == 7 ? collect($earnings)->pluck('data')->toArray() : $earnings->values()->toArray());

            $otp_history_labels =  $request->er_filter == 7 ? collect($otpdata)->pluck('labels') : $otpdata->keys();
            $otp_history_data = array_map(fn ($value) => $value ?? 0, $request->er_filter == 7 ? collect($otpdata)->pluck('data')->toArray() : $otpdata->values()->toArray());

            $jobs_labels =  $request->er_filter == 7 ? collect($jobsdata)->pluck('labels') : $jobsdata->keys();
            $jobs_data = array_map(fn ($value) => $value ?? 0, $request->er_filter == 7 ? collect($jobsdata)->pluck('data')->toArray() : $jobsdata->values()->toArray());

            return response()->json([

                'userlabels' => $userlabels,
                'providers' => $providers,
                'seekers' => $seekers,

                'earning_labels' => $earning_labels,
                'earning_data' => $earning_data,

                'jobs_labels' => $jobs_labels,
                'jobs_data' => $jobs_data,

                'otp_history_labels' => $otp_history_labels,
                'otp_history_data' => $otp_history_data

            ], 200);
        }
        return view('admin.dashboard.index', compact('total_providers', 'total_seekers', 'total_spam_requests', 'total_jobs','total_avail_jobs', 'total_closed_jobs', 'total_fsp_jobs','total_disputes'));
    }
    public function update(Request $request, string $id)
    {
        //
    }
}
