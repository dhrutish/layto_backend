<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function profile(Request $req)
    {
        return view('admin.settings.profile');
    }
    public function changeprofile(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'mobile' => 'required|numeric|unique:users,mobile,' . auth()->user()->id,
            'image' => 'image|mimes:png,jpg,jpeg',
        ], [
            '*.required' => trans('messages.field_required'),
            'email.email' => trans('messages.valid_email'),
            'email.unique' => trans('messages.email_exist'),
            'mobile.numeric' => trans('messages.numeric_only'),
            'mobile.unique' => trans('messages.mobile_exist'),
            'image.image' => trans('messages.valid_image'),
            'image.mimes' => trans('messages.valid_image_type'),
        ]);
        try {
            $cu = User::find(auth()->user()->id);
            $cu->name = $req->name;
            $cu->email = $req->email;
            $cu->mobile = $req->mobile;
            if ($req->hasFile('image')) {
                if (auth()->user()->image != 'default.png' && file_exists('storage/app/public/admin/assets/images/profile/' . auth()->user()->image)) {
                    unlink('storage/app/public/admin/assets/images/profile/' . auth()->user()->image);
                }
                $image = 'profile-' . uniqid() . '.' . $req->image->getClientOriginalExtension();
                $req->image->move(storage_path('app/public/admin/assets/images/profile/'), $image);
                $cu->image = $image;
            }
            $cu->save();
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }
    public function changepassword(Request $req)
    {
        $req->validate([
            'current_password' => 'required',
            'new_password' => 'required|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ], [
            '*.required' => trans('messages.field_required'),
            'new_password.different' => trans('messages.new_password_diffrent'),
            'confirm_password.same' => trans('messages.confirm_password_same')
        ]);
        if (Hash::check($req->current_password, auth()->user()->password)) {
            User::where('id', auth()->user()->id)->update(['password' => Hash::make($req->new_password)]);
            return redirect()->back()->with('success', trans('messages.success'));
        } else {
            return redirect()->back()->with('error', trans('messages.old_password_invalid'));
        }
    }
    public function resetpassword(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'userid' => 'required|exists:users,id',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], [
            '*.required' => trans('messages.field_required'),
            'userid.*' => trans('messages.invalid_request'),
            'new_password.different' => trans('messages.new_password_diffrent'),
            'confirm_password.same' => trans('messages.confirm_password_same')
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        $cu = User::where('id', $req->userid)->where('is_available', 1)->first();
        if (empty($cu)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        try {
            $cu->password = Hash::make($req->new_password);
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
    }

    public function gsettings(Request $req)
    {
        return view('admin.settings.general_settings');
    }
    public function gsettingsupdate(Request $req)
    {
        $req->validate([
            'sign_up_seeker' => 'required|numeric',
            'sign_up_provider' => 'required|numeric',
            'job_post_coins' => 'required|numeric',
            'apply_job_coins' => 'required|numeric',
            'seeker_connect_coins' => 'required|numeric',
            'referral_coins' => 'required|numeric',
            'profile_switch_coins' => 'required|numeric',
            'direct_contact_coins' => 'required|numeric',
            'job_auto_close_days' => 'required|numeric',
            'coin_expire_days' => 'required|numeric',
            'gst' => 'required_if:is_gst_included,1|max:100',
        ], [
            '*.required' => trans('messages.field_required'),
            '*.numeric' => trans('messages.numeric_only'),
            'gst.max' => trans('messages.gst_invalid'),
        ]);
        try {
            $s = Settings::first();
            $s->sign_up_seeker = $req->sign_up_seeker;
            $s->sign_up_provider = $req->sign_up_provider;
            $s->job_post_coins = $req->job_post_coins;
            $s->apply_job_coins = $req->apply_job_coins;
            $s->seeker_connect_coins = $req->seeker_connect_coins;
            $s->referral_coins = $req->referral_coins;
            $s->profile_switch_coins = $req->profile_switch_coins;
            $s->direct_contact_coins = $req->direct_contact_coins;
            $s->job_auto_close_days = $req->job_auto_close_days;
            $s->coin_expire_days = $req->coin_expire_days;
            $s->is_gst_included = $req->is_gst_included == 1 ? 1 : 2;
            $s->gst = $req->is_gst_included == 1 ? $req->gst : 0;
            $s->save();
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }
    public function esettings(Request $req)
    {
        return view('admin.settings.email_settings');
    }
    public function esettingsupdate(Request $req)
    {
        $req->validate([
            'mailer' => 'required',
            'host' => 'required',
            'port' => 'required',
            'encryption' => 'required',
            'username' => 'required',
        ], [
            '*.required' => trans('messages.field_required'),
        ]);
        try {
            $envfile = base_path('.env');
            file_put_contents($envfile, str_replace('MAIL_MAILER=' . env('MAIL_MAILER'), 'MAIL_MAILER=' . $req->mailer, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('MAIL_HOST=' . env('MAIL_HOST'), 'MAIL_HOST=' . $req->host, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('MAIL_PORT=' . env('MAIL_PORT'), 'MAIL_PORT=' . $req->port, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('MAIL_ENCRYPTION=' . env('MAIL_ENCRYPTION'), 'MAIL_ENCRYPTION=' . $req->encryption, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('MAIL_USERNAME=' . env('MAIL_USERNAME'), 'MAIL_USERNAME=' . $req->username, file_get_contents($envfile)));
            if ($req->filled('password')) {
                file_put_contents($envfile, str_replace('MAIL_PASSWORD=' . env('MAIL_PASSWORD'), 'MAIL_PASSWORD=' . $req->password, file_get_contents($envfile)));
            }
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }
    public function psettings(Request $req)
    {
        return view('admin.settings.payment_settings');
    }
    public function psettingsupdate(Request $req)
    {
        $req->validate([
            'public_key' => 'required',
            'secret_key' => 'required',
        ], [
            '*.required' => trans('messages.field_required'),
        ]);
        try {
            $envfile = base_path('.env');
            file_put_contents($envfile, str_replace('RAZORPAY_PUBLIC_KEY=' . env('RAZORPAY_PUBLIC_KEY'), 'RAZORPAY_PUBLIC_KEY=' . $req->public_key, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('RAZORPAY_SECRET_KEY=' . env('RAZORPAY_SECRET_KEY'), 'RAZORPAY_SECRET_KEY=' . $req->secret_key, file_get_contents($envfile)));
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }
    public function privacy_policy(Request $req)
    {
        $type = 1;
        return view('admin.cms.index', compact('type'));
    }
    public function terms_conditions(Request $req)
    {
        $type = 2;
        return view('admin.cms.index', compact('type'));
    }
    public function report_spam(Request $req)
    {
        $type = 3;
        return view('admin.cms.index', compact('type'));
    }
    public function updatecms(Request $req)
    {
        $req->validate([
            'type' => 'bail|required|in:1,2,3',
            'content' => 'required',
        ], [
            'type.required' => trans('messages.invalid_request'),
            'type.in' => trans('messages.invalid_request'),
            '*.required' => trans('messages.field_required'),
        ]);
        try {
            $d = CMS::where('type', $req->type)->first();
            if (empty($d)) {
                $d = new CMS();
                $d->type = $req->type;
            }
            $d->content = $req->content;
            $d->save();
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }

    public function fsettings(Request $req)
    {
        return view('admin.settings.firebase_settings');
    }
    public function fsettingsupdate(Request $req)
    {
        $req->validate([
            'secret_key' => 'required',
        ], [
            '*.required' => trans('messages.field_required'),
        ]);
        try {
            $envfile = base_path('.env');
            file_put_contents($envfile, str_replace('FIREBASE_KEY=' . env('FIREBASE_KEY'), 'FIREBASE_KEY=' . $req->secret_key, file_get_contents($envfile)));
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }

    public function tsettings(Request $req)
    {
        return view('admin.settings.twilio_settings');
    }
    public function tsettingsupdate(Request $req)
    {
        $req->validate([
            'account_sid' => 'required',
            'auth_token' => 'required',
            'phone_number' => 'required',
        ], [
            '*.required' => trans('messages.field_required'),
        ]);
        try {
            $envfile = base_path('.env');
            file_put_contents($envfile, str_replace('TWILIO_ACCOUNT_SID=' . env('TWILIO_ACCOUNT_SID'), 'TWILIO_ACCOUNT_SID=' . $req->account_sid, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('TWILIO_AUTH_TOKEN=' . env('TWILIO_AUTH_TOKEN'), 'TWILIO_AUTH_TOKEN=' . $req->auth_token, file_get_contents($envfile)));
            file_put_contents($envfile, str_replace('TWILIO_PHONE_NUMBER=' . env('TWILIO_PHONE_NUMBER'), 'TWILIO_PHONE_NUMBER=' . $req->phone_number, file_get_contents($envfile)));
            return redirect()->back()->with('success', trans('messages.success'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', trans('messages.error'));
        }
    }
}
