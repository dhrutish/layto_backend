<?php

use App\Models\Areas;
use App\Models\CMS;
use App\Models\IdProof;
use App\Models\Settings;
use App\Models\Skills;
use App\Models\Notifications;
use App\Models\Transactions;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

if (!function_exists('image_path')) {
    function image_path($image)
    {
        $path = '';
        if (Str::contains($image, 'default') || Str::contains($image, 'profile')) {
            $path = url('storage/app/public/admin/assets/images/profile/' . $image);
        } else if (Str::contains($image, 'advertisings') || Str::contains($image, 'news-')) {
            $path = url('storage/app/public/admin/assets/images/advertisings/' . $image);
        } else if (Str::contains($image, 'resume')) {
            $path = url('storage/app/public/admin/assets/images/resumes/' . $image);
        } else if (Str::contains($image, 'login') || Str::contains($image, 'in') || Str::contains($image, 'out')) {
            $path = url('storage/app/public/admin/assets/images/' . $image);
        } else if (Str::contains($image, 'logo')) {
            $path = url('storage/app/public/admin/assets/img/icons/' . $image);
        } else {
            $path = url('storage/app/public/admin/assets/images/nodata.png');
        }
        if (!get_headers($path) || strpos(get_headers($path)[0], '200') == false) {
            $path = url('storage/app/public/admin/assets/images/nodata.png');
        }
        return $path;
    }
}
if (!function_exists('currency_formated')) {
    function currency_formated($a)
    {
        return number_format($a, 2) . '/₹';
    }
}
if (!function_exists('status_badge')) {
    function status_badge($status)
    {
        if ($status == 1) {
            $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.available') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
        } else {
            $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.not_available') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
        }
        return $status;
    }
}
if (!function_exists('random_password')) {
    function random_password()
    {
        return substr(str_shuffle('A1@B2#C3$D4%E5&F6G7H8I9JKL0MNOPQR@STUVWXYZ'), 0, rand(6, 8));
    }
}
if (!function_exists('settingsdata')) {
    function settingsdata()
    {
        return Settings::first();
    }
}
if (!function_exists('date_formated')) {
    function date_formated($date)
    {
        return date('d M, Y', strtotime($date));
    }
}
if (!function_exists('time_formated')) {
    function time_formated($time)
    {
        return date('h:i A', strtotime($time));
    }
}
if (!function_exists('date_time_formated')) {
    function date_time_formated($time)
    {
        return date('d M, Y | h:i A', strtotime($time));
    }
}
if (!function_exists('cmsdata')) {
    function cmsdata($type)
    {
        if (in_array($type, [1, 2, 3])) {
            return optional(CMS::select('content')->where('type', $type)->first())->content ?? '';
        }
        return CMS::select('id', 'type', 'content')->get();
    }
}
if (!function_exists('form_action_buttons')) {
    function form_action_buttons($cancel_url)
    {
        $html = '<div class="hstack gap-2"> <button class="btn btn-primary" type="submit">' . trans('labels.submit') . '</button> ';
        if (!empty($cancel_url)) {
            $html .= '<div class="vr bg-200"></div><a class="btn btn-outline-danger" type="button" href="' . $cancel_url . '">' . trans('labels.cancel') . '</a>';
        }
        $html .= '</div>';
        return $html;
    }
}
if (!function_exists('send_password')) {
    function send_password($title, $email, $password, $users_name)
    {
        try {
            $data = ['title' => $title, 'email' => $email, 'password' => $password, 'users_name' => $users_name];
            Mail::send('emails.send_password', $data, function ($message) use ($data) {
                $message->from(env('MAIL_USERNAME'))->subject($data['title']);
                $message->to($data['email']);
            });
            return 1;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
if (!function_exists('email_verification')) {
    function email_verification($title, $email, $otp)
    {
        try {
            $data = ['title' => $title, 'email' => $email, 'otp' => $otp];
            Mail::send('emails.email_verification', $data, function ($message) use ($data) {
                $message->from(env('MAIL_USERNAME'))->subject($data['title']);
                $message->to($data['email']);
            });
            return $otp;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
if (!function_exists('verificationsms')) {
    function verificationsms($mobile)
    {
        try {
            $cnt = UserOtp::where('mobile', $mobile)->whereDate('created_at', date('Y-m-d'))->count();
            if ($cnt <= 2) {
                $otp = rand(1000, 9999);

                // $twilio = new Client(config('app.twilio_sid'), config('app.twilio_auth_token'));
                // $twilio->messages->create(
                //     '+91' . $mobile,
                //     [
                //         'from' => config('app.twilio_phone_number'),
                //         'body' => "Your OTP for registration is: $otp",
                //     ]
                // );

                // $msg91_template_id = config('app.msg91_template_id');
                // $msg91_authkey = config('app.msg91_authkey');
                // $curl = curl_init();
                // curl_setopt_array($curl, array(
                //     CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=".$msg91_template_id."&mobile=91".$mobile."&authkey=".$msg91_authkey."",
                //     CURLOPT_RETURNTRANSFER => true,
                //     CURLOPT_ENCODING => "",
                //     CURLOPT_MAXREDIRS => 10,
                //     CURLOPT_TIMEOUT => 30,
                //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                //     CURLOPT_CUSTOMREQUEST => "GET",
                //     CURLOPT_HTTPHEADER => array("content-type: application/json"),
                // ));
                // $response = curl_exec($curl);
                // $err = curl_error($curl);
                // curl_close($curl);

                $otphistory = new UserOtp;
                $otphistory->mobile = $mobile;
                $otphistory->save();
                return $otp;
            }
            return 2;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
if (!function_exists('notifysms')) {
    function notifysms($mobile, $content)
    {
        try {
            // Days range only for 400 days
            // $client = new Client(config('app.twilio_sid'), config('app.twilio_auth_token'));
            // $messages = $client->messages->stream(array('dateSentAfter' => '2022-10-01','dateSentBefore' => '2023-07-28'));
            // $filename = config('app.twilio_sid')."_sms.csv";
            // header("Content-Type: application/csv");
            // header("Content-Disposition: attachment; filename={$filename}");
            // $fields = array( 'SMS Message SID', 'From', 'To', 'Date Sent', 'Status', 'Direction', 'Price', 'Body' );
            // echo '"'.implode('","', $fields).'"'."\n";
            // foreach ($messages as $sms) {
            //     $row = array($sms->sid,$sms->from,$sms->to,$sms->dateSent->format('Y-m-d H:i:s'),$sms->status,$sms->direction,$sms->price,$sms->body);
            //     echo '"'.implode('","', $row).'"'."\n";
            // }

            // $twilio = new Client(config('app.twilio_sid'), config('app.twilio_auth_token'));
            // dd($twilio->messages->read());
            // $otpHistory = [];
            // foreach ($twilio->messages->read() as $message) {
            //     $otpHistory[] = $message;
            //     // if ($this->isOTPMessage($message->body)) {
            //     //     $otpHistory[] = [
            //     //         'from' => $message->from,
            //     //         'to' => $message->to,
            //     //         'otp' => $this->extractOTP($message->body),
            //     //         'date' => $message->dateSent->format('Y-m-d H:i:s'),
            //     //     ];
            //     // }
            // }
            // dd($otpHistory);

            $twilio = new Client(config('app.twilio_sid'), config('app.twilio_auth_token'));
            $msg = $twilio->messages->create(
                '+91' . $mobile,
                [
                    'from' => config('app.twilio_phone_number'),
                    'body' => $content,
                    // 'content_type' => 'text/html',
                ]
            );
            return $msg->sid;
        } catch (\Throwable $th) {
            dd($th);
            return false;
        }
    }
}
if (!function_exists('errorResponse')) {
    function errorResponse($error = null)
    {
        if (!env('APP_DEBUG')) {
            return response()->json(['status' => 0, 'message' => 'Something Went Wrong..!!'], 200);
        } else {
            return response()->json(['status' => 0, 'message' => 'Something Went Wrong..!!', 'error' => $error], 200);
        }
    }
}
if (!function_exists('pendingproofs')) {
    function pendingproofs($type)
    {
        return IdProof::where('status', 1)->count();
    }
}
if (!function_exists('userCoins')) {
    function userCoins($user_id)
    {
        // 1 = SignUpJobProvider(UP),
        // 2 = SignUpJobSeeker(UP),
        // 3 = CreditedByAdmin(UP),
        // 6 = Plan/Coins-Purchased(UP),
        // 9 = Referral(UP),
        // 11 = JobClosed/Spammed(UP),

        // 4 = DeductedByAdmin(DOWN),
        // 5 = JobPosted(DOWN),
        // 7 = SwitchProfileToJobSeeker(DOWN),
        // 8 = SwitchProfileToJobProvider(DOWN),
        // 10 = ProposalToJobSeeker(DOWN),
        // 12 = JobApplied(DOWN),

        $transactions = Transactions::where('user_id', $user_id)->whereIn('type', [1, 2, 3, 6, 9, 11])->get();
        $totalAvailableCoins = 0;
        foreach ($transactions as $transaction) {
            if (in_array($transaction->is_coins_used, [1, 4])) {
            } elseif ($transaction->is_coins_used == 2) {
                $totalAvailableCoins += $transaction->final_coins;
            } elseif ($transaction->is_coins_used == 3) {
                $totalAvailableCoins += ($transaction->final_coins - $transaction->used_coins);
            }
        }
        return $totalAvailableCoins;
    }
}
if (!function_exists('deductCoins')) {
    function deductCoins($user_id, $coins)
    {
        try {
            $transactions = Transactions::where('user_id', $user_id)->whereIn('type', [1, 2, 3, 6, 9, 11])->whereNotIn('is_coins_used', [1, 4])->orderBy('id', 'asc')->get();
            $remainingCoins = $coins;
            foreach ($transactions as $transaction) {
                if ($remainingCoins > 0) {
                    $availableCoins = $transaction->final_coins - (int)$transaction->used_coins;
                    $coinsToDeduct = min($remainingCoins, $availableCoins);
                    $remainingCoins -= $coinsToDeduct;
                    if ($coinsToDeduct > 0) {
                        $transaction->is_coins_used = 3;
                        $transaction->used_coins += $coinsToDeduct;
                        $transaction->save();
                    }
                    if ($transaction->used_coins == $transaction->final_coins) {
                        $transaction->is_coins_used = 1;
                        $transaction->save();
                    }
                } else {
                    break;
                }
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
if (!function_exists('transactionDescription')) {
    function transactionDescription()
    {
        return ['Employer signed up', 'Job seeker signed up', 'Admin credited coins', 'Admin deducted coins', 'Job posted', 'Purchased subscription Plan/coins', 'Switched to job seeker profile', 'Switched to job provider profile', 'Referral Earning', 'Proposal submitted to seeker', 'Job closed or marked as spam(Refund)', 'Job applied',];
    }
}
if (!function_exists('otherSkillsCount')) {
    function otherSkillsCount()
    {
        return Skills::where('type', 2)->count();
    }
}
if (!function_exists('otherAreasCount')) {
    function otherAreasCount()
    {
        return Areas::where('type', 2)->count();
    }
}
if (!function_exists('store_notification')) {
    function store_notification($user_id, $job_id, $provider_id, $title, $description, $type)
    {
        // 1=ForAdmin,2=ForAll,3=ForProvider,4=ForSeeker
        try {
            Notifications::create(['user_id' => $user_id, 'job_id' => $job_id, 'provider_id' => $provider_id, 'title' => $title, 'description' => $description, 'type' => $type,]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
if (!function_exists('send_notification')) {
    function send_notification($gettokens, $title, $description)
    {
        try {
            $fields = [
                'registration_ids' => $gettokens,
                'notification' => ['title' => $title, 'body' => $description, 'click_action' => 'FLUTTER_NOTIFICATION_CLICK'],
                'data' => [
                    "NotificationId" => substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10),
                    "extra_param" => 'Hii',
                ]
            ];
            $headers = ['Authorization: key=' . env('FIREBASE_KEY'), 'Content-Type: application/json'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
