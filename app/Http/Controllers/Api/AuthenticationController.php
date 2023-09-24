<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required|string',
        ]);
        try {
            $user = User::where('mobile', $request->mobile)->whereIn('type', [3, 4])->first();
            if (!empty($user) && Hash::check($request->password, $user->password)) {
                $token = $user->createToken('api_token')->plainTextToken;
                $userdata = User::with([
                    $user->type == 3 ? 'locations.states' : 'location.states',
                    $user->type == 3 ? 'locations.cities' : 'location.cities',
                    $user->type == 3 ? 'locations.areas' : 'location.areas',
                    'other_info.industry',
                    'other_info.availability',
                    'other_info.education',
                    'categories.category',
                    'skills.skill',
                    'current_working',
                    'id_proof_details',
                    'resume'
                ])->where('id', $user->id)->first();
                return response()->json([
                    'status' => 1,
                    'message' => 'Success',
                    'token' => $token,
                    'userdata' => $userdata,
                    'average_feedback' => $userdata->FeedbacksAvg(),
                    'profile_complete' => $this->isProfileComplete($userdata),
                ], 200);
            } else {
                return response()->json(['status' => 0, 'message' => 'Invalid Login Credential'], 200);
            }
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }

    public function isProfileComplete($user)
    {
        $userdata = is_int($user) ? User::find($user) : $user;
        $requiredFields = [
            'name', 'mobile', 'locations', 'id_proof_details'
        ];
        if ($userdata->type == 4) {
            $requiredFields = array_merge($requiredFields, [
                'gender', 'industry_types_id', 'categories', 'skills', 'availabilities_id', 'exp_salary_from', 'exp_salary_to'
            ]);
            if ($userdata->other_info && $userdata->other_info->experience_type == 3 && count($userdata->work_experience) == 0) {
                $requiredFields[] = 'work_experience';
            }
        }
        foreach ($requiredFields as $field) {
            if (in_array($field, ['categories', 'skills', 'locations', 'work_experience'])) {
                if (count($userdata->$field) == 0) {
                    return false;
                }
            } else if (in_array($field, ['gender', 'industry_types_id', 'availabilities_id', 'exp_salary_from', 'exp_salary_to'])) {
                if (empty($userdata->other_info->$field)) {
                    return false;
                }
            } else {
                if (empty($userdata->$field)) {
                    return false;
                }
            }
        }
        return true;
    }



    public function register(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:3,4',
                'name' => 'required',
                'mobile' => 'required|unique:users,mobile',
                'email' => 'required|unique:users,email',
                'password' => ['required', 'string', new PasswordRule],
                'confirm_password' => 'required|same:password',
                'is_email_verified' => 'required',
                'is_mobile_verified' => 'required',
            ]);

            $checkref = User::where('referral_code', $request->referral_code)->whereIn('type', [3, 4])->available()->first();
            if ($request->filled('referral_code') && empty($checkref)) {
                return response()->json(['status' => 0, 'message' => 'Referral code is Invalid'], 200);
            }

            $user = new User();
            $user->type = $request->type;
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->is_email_verified = $request->is_email_verified;
            $user->is_mobile_verified = $request->is_mobile_verified;
            $user->password = Hash::make($request->password);
            $user->save();

            $tr = new Transactions();
            $tr->type = $user->type == 3 ? 1 : 2;
            $tr->user_id = $user->id;
            $tr->final_coins = $user->type == 3 ? settingsdata()->sign_up_provider : settingsdata()->sign_up_seeker;
            $tr->coin_expire_days = settingsdata()->coin_expire_days;
            $tr->save();

            if ($request->filled('referral_code') && !empty($checkref)) {
                $tr = new Transactions();
                $tr->type = 9;
                $tr->user_id = $checkref->id;
                $tr->refer_user_id = $user->id;
                $tr->final_coins = settingsdata()->referral_coins;
                $tr->coin_expire_days = settingsdata()->coin_expire_days;
                $tr->save();
            }
            return response()->json(['status' => 1, 'message' => 'Success', 'userdata' => User::find($user->id)], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function sendtextotp(Request $request)
    {
        $request->validate(['mobile' => 'required']);
        $sms = verificationsms($request->mobile);
        if ($sms == false) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong!'], 200);
        } elseif ($sms == 2) {
            return response()->json(['status' => 0, 'message' => 'You have exceeded the maximum number of OTP requests. Please try again after 24 hours.'], 200);
        } else {
            return response()->json(['status' => 1, 'message' => 'Success', 'otp' => $sms], 200);
        }
    }
    public function sendemailotp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $esms = email_verification('Email verification', $request->email, rand(1000, 9999));
        if ($esms == false) {
            return response()->json(['status' => 0, 'message' => 'Something went wrong!'], 200);
        } else {
            return response()->json(['status' => 1, 'message' => 'Success', 'otp' => $esms], 200);
        }
    }
    public function forgot_password(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => ['required', 'string', new PasswordRule],
            'confirm_password' => 'required|same:password',
        ]);

        $user = User::Where('mobile', $request->mobile)->first();
        if (!empty($user)) {
            $user->password = Hash::make($request->password);
            $user->save();
            $user->tokens()->delete();
            return response()->json(['status' => 1, 'message' => 'Successfull'], 200);
        } else {
            return response()->json(['status' => 0, 'message' => 'User not found'], 200);
        }
    }
    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', new PasswordRule],
            'password' => ['required', 'string', new PasswordRule],
            'confirm_password' => 'required|same:password',
        ]);
        $user = auth('sanctum')->user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['status' => 1, 'message' => 'Successfull'], 200);
        } else {
            return response()->json(['status' => 0, 'message' => 'Current password is Invalid.'], 200);
        }
    }
    public function google_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'type' => 'required|in:3,4',
            'is_email_verified' => 'required|in:true,false',
            'uid' => 'required',
        ]);
        try {
            $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email)->orWhere('social_login_id', $request->uid);
            })->whereIn('type', [3, 4])->available()->first();
            if (empty($user)) {
                $user = new User();
                $user->type = $request->type;
                $user->login_type = 2;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile = $request->mobile ?? "";
                $user->fcm_token = $request->fcm_token ?? "";
                $user->social_login_id = $request->uid;
                $user->is_email_verified = $request->is_email_verified == true ? 1 : 2;
                $user->save();
            }
            $token = $user->createToken('api_token')->plainTextToken;
            $userdata = User::with([
                $user->type == 3 ? 'locations.states' : 'location.states',
                $user->type == 3 ? 'locations.cities' : 'location.cities',
                $user->type == 3 ? 'locations.areas' : 'location.areas',
                'other_info.industry',
                'other_info.availability',
                'other_info.education',
                'categories.category',
                'skills.skill',
                'current_working',
                'id_proof_details',
                'resume'
            ])->where('id', $user->id)->first();
            return response()->json([
                'status' => 1,
                'message' => 'Success',
                'token' => $token,
                'userdata' => $userdata,
                'average_feedback' => $userdata->FeedbacksAvg(),
                'profile_complete' => $this->isProfileComplete($userdata),
            ], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function apple_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'type' => 'required|in:3,4',
            'is_email_verified' => 'required|in:true,false',
            'uid' => 'required',
        ]);
        try {
            $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->email)->orWhere('social_login_id', $request->uid);
            })->whereIn('type', [3, 4])->available()->first();
            if (empty($checkuser)) {
                $user = new User();
                $user->type = $request->type;
                $user->login_type = 2;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->mobile = $request->mobile ?? "";
                $user->fcm_token = $request->fcm_token ?? "";
                $user->social_login_id = $request->uid;
                $user->is_email_verified = $request->is_email_verified == true ? 1 : 2;
                $user->save();
            }
            $token = $user->createToken('api_token')->plainTextToken;
            $userdata = User::with([
                $user->type == 3 ? 'locations.states' : 'location.states',
                $user->type == 3 ? 'locations.cities' : 'location.cities',
                $user->type == 3 ? 'locations.areas' : 'location.areas',
                'other_info.industry',
                'other_info.availability',
                'other_info.education',
                'categories.category',
                'skills.skill',
                'current_working',
                'id_proof_details',
                'resume'
            ])->where('id', $user->id)->first();
            return response()->json([
                'status' => 1,
                'message' => 'Success',
                'token' => $token,
                'userdata' => $userdata,
                'average_feedback' => $userdata->FeedbacksAvg(),
                'profile_complete' => $this->isProfileComplete($userdata),
            ], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function checkvaliddata(Request $request)
    {
        try {
            $request->validate([
                'mobile' => 'required|unique:users,mobile',
                'email' => 'required|unique:users,email',
                'referral_code' => 'sometimes|valid_referral_code',
                'password' => ['required', 'string', new PasswordRule],
                'confirm_password' => 'required|same:password',
            ], [
                'referral_code.valid_referral_code' => 'Referral code is Invalid',
            ]);
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function logout(Request $request)
    {
        $token = explode('|', $request->bearerToken());
        auth('sanctum')->user()->tokens()->find($token[0])->delete();
        return response()->json(['status' => 1, 'message' => 'Logged out successfully']);
    }
    public function getuser()
    {
        $user = auth('sanctum')->user();
        if (empty($user)) {
            return response()->json(['status' => 1, 'message' => 'Data not found'], 200);
        }
        $userdata = User::with([
            $user->type == 3 ? 'locations.states' : 'location.states', $user->type == 3 ? 'locations.cities' : 'location.cities', $user->type == 3 ? 'locations.areas' : 'location.areas', 'other_info.industry', 'other_info.availability', 'other_info.education', 'categories.category', 'skills.skill', 'current_working', 'id_proof_details', 'resume'
        ])->where('id', $user->id);
        $userdata = $userdata->first();
        return response()->json(['status' => 1, 'message' => 'Success', 'userdata' => $userdata, 'average_feedback' => $userdata->FeedbacksAvg()], 200);
    }
    public function coins_settings()
    {
        return response()->json(['status' => 1, 'message' => 'Success', 'data' => settingsdata()], 200);
    }
}
