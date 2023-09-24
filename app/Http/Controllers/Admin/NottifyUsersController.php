<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NotifyUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class NottifyUsersController extends Controller
{
    public function index()
    {
        return view('admin.notifyusers.index');
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'noti_type' => 'bail|required|in:1,2,3',
            'title' => 'max:50',
            'comma_values' => 'required_if:user_type,4',
            'description' => 'bail|required',
            // 'description' => 'bail|required' . ($request->noti_type == 2 ? '|max:50' : ''),
        ], [
            '*.required' => trans('messages.field_required'),
            '*.required_if' => trans('messages.field_required'),
            '*.max' => trans('messages.text_limit_exceeded'),
            'type.in' => trans('messages.invalid_request'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            $description = $request->description;
            $title = $request->title != "" ? $request->title : env('APP_NAME');
            $cu = [];
            if ($request->user_type == 4) {
                $cu = User::select('id', 'name', 'email', 'mobile', 'fcm_token', 'type')->where('is_available', 1)->whereIn('type', [3, 4])->whereIn($request->user_type == 1 ? 'email' : 'mobile', explode(',', $request->comma_values))->get();
                if (count($cu) <= 0) {
                    return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
                }
            } elseif ($request->user_type == 3) {
                $cu = User::select('id', 'name', 'email', 'mobile', 'fcm_token', 'type')->where('is_available', 1)->whereIn('type', [3, 4])->get();
            } else {
                $cu = User::select('id', 'name', 'email', 'mobile', 'fcm_token', 'type')->where('is_available', 1)->where('type', $request->user_type + 2)->get();
            }
            if ($request->noti_type == 1) {
                foreach ($cu as $key => $user) {
                    $data = ['email' => $user->email];
                    $data1 = ['email' => $user->email, 'title' => $title, 'messages' => $description, 'name' => $user->name];
                    Mail::send('emails.notify_users', $data, function ($message) use ($data) {
                        $message->from(env('MAIL_USERNAME'))->subject($data['title']);
                        $message->to($data['email']);
                    });
                    // Mail::to($data)->queue(new NotifyUsers($data1));
                    // Mail::to($data)->cc($moreUsers)->bcc($evenMoreUsers)->queue(new NotifyUsers($data1));
                }
            }
            if ($request->noti_type == 2) {
                send_notification($cu->pluck('fcm_token'), $title, $description);
                foreach ($cu as $key => $user) {
                    store_notification($user->id, $job_id = null, $provider_id = null, $title, $description, 2);
                }
            }
            if ($request->noti_type == 3) {
                foreach ($cu as $key => $user) {
                    notifysms($user->mobile, str_replace('&nbsp;', ' ', strip_tags($description)));
                }
            }
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
