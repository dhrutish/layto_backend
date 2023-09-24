<?php

namespace App\Console\Commands;

use App\Models\Advertising;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExpireAdvertisement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advertise-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Kolkata');
        $data = Advertising::get();
        foreach ($data as $advertisement) {
            $ex_time = Carbon::parse($advertisement->expiry_date);
            $now = Carbon::now();
            if ($ex_time->lt($now)) {
                Storage::delete('public/admin/assets/images/advertisings/' . $advertisement->file);
                $advertisement->delete();
                $this->info("===== The **$advertisement->title** titled advertise has been deleted =====");
            }
        }
    }
}
