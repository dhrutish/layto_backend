<?php

namespace App\Console\Commands;

use App\Models\Jobs;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoExpireCoins extends Command
{
    protected $signature = 'auto-expire-coins';
    protected $description = 'Command description';
    public function handle()
    {
        // 1=SignUpJobProvider(UP),
        // 2=SignUpJobSeeker(UP),
        // 3=CreditedByAdmin(UP),
        // 6=Plan/Coins-Purchased(UP),
        // 9=Referral(UP),
        // 11=JobClosed/Spammed(UP),

        // 4=DeductedByAdmin(DOWN),
        // 5=JobPosted(DOWN),
        // 7=SwitchProfileToJobSeeker(DOWN),
        // 8=SwitchProfileToJobProvider(DOWN),
        // 10=ProposalToJobSeeker(DOWN),
        // 12=JobApplied(DOWN),

        date_default_timezone_set('Asia/Kolkata');

        $get_tr = Transactions::whereIn('type', [1, 2, 3, 6, 9, 11])->whereIn('is_coins_used', [2, 3])->oldest()->get()->makeVisible(['created_at']);

        foreach ($get_tr as $key => $transaction) {
            $coin_date = Carbon::parse($transaction->created_at);
            $coin_expiry_date = $coin_date->addDays($transaction->coin_expire_days);
            $current_date = Carbon::now();
            if ($coin_expiry_date->lt($current_date)) {
                $transaction->is_coins_used = 4;
                $transaction->save();
                $t = $transaction->final_coins - $transaction->used_coins;
                $this->info("======== The Credited Coins : ($transaction->final_coins($t)) to ID : ($transaction->id) has been Expired with Autometic expire functionality ========");
            }
        }
    }
}
