<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function cmspages(Request $request)
    {
        try {
            return response()->json(['status' => 1, 'message' => "Successful","privacy_policy" => cmsdata(1) ?? '',"terms_condition" => cmsdata(2) ?? '',"report_spam" => cmsdata(3) ?? ''], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function razorpay(Request $request)
    {
        try {
            return response()->json(['status' => 1, 'message' => 'Success', 'public_key' => env('RAZORPAY_PUBLIC_KEY'), 'secret_key' => env('RAZORPAY_SECRET_KEY')]);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
