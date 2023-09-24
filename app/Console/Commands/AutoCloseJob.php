<?php

namespace App\Console\Commands;

use App\Models\Jobs;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCloseJob extends Command
{
    protected $signature = 'auto-close-job';
    protected $description = 'Command description';
    public function handle()
    {
        date_default_timezone_set('Asia/Kolkata');
        $getjobs = Jobs::where('status', 1)->oldest()->get()->makeVisible(['created_at']);
        foreach ($getjobs as $key => $job) {
            $job_date = Carbon::parse($job->created_at);
            if ($job->is_reposted == 1) {
                $job_date = Carbon::parse($job->reposted_on);
            }
            $job_expiry_date = $job_date->addDays($job->job_auto_close_days);
            if ($job_expiry_date->lt($current_date)) {
                $current_date = Carbon::now();
                $job->status = 4;
                $job->closed_at = $current_date;
                $job->save();
                $this->info("======== The Job : $job->title ($job->id) has been closed with Autometic close functionality ========");
            }
        }
    }
}
