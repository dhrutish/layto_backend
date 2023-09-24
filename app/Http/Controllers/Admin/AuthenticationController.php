<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && in_array(Auth::user()->type, [1, 2])) {
                return redirect('dashboard');
            }
            return $next($request);
        });
    }
    public function index()
    {
        return view('admin.auth.login');
    }
    public function checkadmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => trans('messages.field_required'),
            'email.email' => trans('messages.valid_email'),
            'password.required' => trans('messages.field_required'),
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            if (in_array(Auth::user()->type, [1, 2])) {
                if (Auth::user()->is_available == 1) {
                    return redirect('dashboard')->with('success', trans('messages.success'));
                } else {
                    Auth::logout();
                    return redirect()->back()->with('error', trans('messages.blocked'));
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', trans('messages.email_pass_invalid'))->withInput();
            }
        } else {
            return redirect()->back()->with('error', trans('messages.email_pass_invalid'))->withInput();
        }
    }
    public function forgotpassword()
    {
        return view('admin.auth.forgotpassword');
    }
    public function sendpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => trans('messages.field_required'),
            'email.email' => trans('messages.valid_email')
        ]);
        $cu = User::select('id', 'name', 'is_available')->where('email', $request->email)->whereIn('type', [1, 2])->first();
        if (empty($cu)) {
            return redirect()->back()->with('error', trans('messages.email_not_found'))->withInput();
        } else if (!empty($cu) && $cu->is_available == 2) {
            return redirect()->back()->with('error', trans('messages.blocked'))->withInput();
        }
        $pass = random_password();
        if (send_password(trans('labels.reset_password'), $request->email, $pass, $cu->name) == 1) {
            $cu->password = Hash::make($pass);
            $cu->save();
            return redirect('admin')->with('success', trans('messages.success'));
        }
        return redirect()->back()->with('error', trans('messages.email_error'))->withInput();
    }
}
