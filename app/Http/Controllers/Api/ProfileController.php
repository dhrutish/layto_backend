<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IdProof;
use App\Models\Locations;
use App\Models\Resumes;
use App\Models\Transactions;
use App\Models\JobApplications;
use App\Models\User;
use App\Models\Jobs;
use App\Models\UserCategories;
use App\Models\UserOtherInfo;
use App\Models\UserSkills;
use App\Models\UserWorkExperience;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\AuthenticationController;

class ProfileController extends Controller
{
    public function update_profile(Request $request)
    {
        $request->validate([
            'image' => 'required|max:5096|image|mimes:png,jpg,jpeg'
        ]);
        try {
            $user = auth('sanctum')->user();
            $filename = $this->uploadProfileImage($request->file('image'));
            $this->deleteProfileImageIfExists($user->back_image);
            $user->image = $filename;
            $user->save();
            return response()->json(['status' => 1, 'message' => "Success"], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_basic_details(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required|min:10|unique:users,mobile,' . auth('sanctum')->user()->id,
            'email' => 'required|email|unique:users,email,' . auth('sanctum')->user()->id,
        ]);
        if ($request->mobile != auth('sanctum')->user()->mobile && in_array($request->is_mobile_verified, ['', 2])) {
            return response()->json(['status' => 0, 'message' => "Something Went Wrong..!!", "error" => ["is_mobile_verified" => "Mobile verification is required."]], 200);
        }
        if ($request->email != auth('sanctum')->user()->email && in_array($request->is_email_verified, ['', 2])) {
            return response()->json(['status' => 0, 'message' => "Something Went Wrong..!!", "error" => ["is_email_verified" => "Email verification is required."]], 200);
        }
        try {
            $user = auth('sanctum')->user();
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->save();
            return response()->json(['status' => 1, 'message' => "Success"], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_additional_details(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:1,2,3',
            'dob' => 'required',
        ]);
        try {
            $user = UserOtherInfo::where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($user)) {
                $user = new UserOtherInfo();
                $user->user_id = auth('sanctum')->user()->id;
            }
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_address(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'address' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
            'pincode' => 'required',
        ]);
        try {
            $address = Locations::where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($address)) {
                $address = new Locations();
                $address->user_id = auth('sanctum')->user()->id;
            }
            $address->address = $request->address;
            $address->state_id = $request->state_id;
            $address->city_id = $request->city_id;
            $address->area_id = $request->area_id;
            $address->pincode = $request->pincode;
            $address->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_aadhar_details(Request $request)
    {
        $request->validate([
            'id_number' => 'required|min:12',
            'front_image' => 'image|mimes:png,jpg,jpeg',
            'back_image' => 'image|mimes:png,jpg,jpeg',
        ]);
        try {
            $proof = IdProof::where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($proof)) {
                $proof = new IdProof();
                $proof->user_id = auth('sanctum')->user()->id;
            }
            $proof->id_number = $request->id_number;
            if ($request->hasFile('front_image')) {
                $front_image = $this->uploadProofImage($request->file('front_image'));
                $this->deleteProofImageIfExists($proof->front_image);
                $proof->front_image = $front_image;
            }
            if ($request->hasFile('back_image')) {
                $back_image = $this->uploadProofImage($request->file('back_image'));
                $this->deleteProofImageIfExists($proof->back_image);
                $proof->back_image = $back_image;
            }
            $proof->status = 1;
            $proof->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_job_preference(Request $request)
    {
        $request->validate([
            'industry_type' => 'required',
            'categories' => 'required',
            'skills' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $preference = UserOtherInfo::where('user_id', auth('sanctum')->user()->id)->first();
            if (!$preference) {
                $preference = new UserOtherInfo();
                $preference->user_id = auth('sanctum')->user()->id;
            }
            $preference->industry_types_id = $request->industry_type;
            $preference->save();
            UserCategories::where('user_id', auth('sanctum')->user()->id)->delete();
            foreach (explode(',', $request->categories) as $category) {
                $user_category = new UserCategories();
                $user_category->user_id = auth('sanctum')->user()->id;
                $user_category->categories_id = $category;
                $user_category->save();
            }
            UserSkills::where('user_id', auth('sanctum')->user()->id)->delete();
            foreach (explode(',', $request->skills) as $skill) {
                $user_skill = new UserSkills();
                $user_skill->user_id = auth('sanctum')->user()->id;
                $user_skill->skills_id = $skill;
                $user_skill->save();
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
    public function edit_job_expectation(Request $request)
    {
        $request->validate([
            'availabilities_id' => 'required',
            'exp_salary_from' => 'required',
            'exp_salary_to' => 'required',
        ]);
        try {
            $expectation = UserOtherInfo::where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($expectation)) {
                $expectation = new UserOtherInfo();
                $expectation->user_id = auth('sanctum')->user()->id;
            }
            $expectation->availabilities_id = $request->availabilities_id;
            $expectation->exp_salary_from = $request->exp_salary_from;
            $expectation->exp_salary_to = $request->exp_salary_to;
            $expectation->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function add_experience(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'designation' => 'required',
            'from' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'to' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'is_currently_working' => 'required|in:1,2',
        ]);
        try {
            if ($request->is_currently_working == 1) {
                UserWorkExperience::where('user_id', auth('sanctum')->user()->id)->update(['is_currently_working' => 2]);
            }
            $work_experince = new UserWorkExperience();
            $work_experince->user_id = auth('sanctum')->user()->id;
            $work_experince->company_name = $request->company_name;
            $work_experince->designation = $request->designation;
            $work_experince->from = $request->from;
            $work_experince->to = $request->to;
            $work_experince->is_currently_working = $request->is_currently_working;
            $work_experince->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_experience(Request $request)
    {
        $request->validate([
            'experience_id' => 'required',
            'company_name' => 'required',
            'designation' => 'required',
            'from' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'to' => 'required|date|date_format:Y-m-d|before_or_equal:today',
            'is_currently_working' => 'required|in:1,2',
        ]);
        try {
            $work_experince = UserWorkExperience::where('id', $request->experience_id)->where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($work_experince)) {
                return response()->json(['status' => 0, 'message' => 'Invalid Experince ID'], 200);
            }
            if ($request->is_currently_working == 1) {
                UserWorkExperience::where('user_id', auth('sanctum')->user()->id)->update(['is_currently_working' => 2]);
            }
            $work_experince->user_id = auth('sanctum')->user()->id;
            $work_experince->company_name = $request->company_name;
            $work_experince->designation = $request->designation;
            $work_experince->from = $request->from;
            $work_experince->to = $request->to;
            $work_experince->is_currently_working = $request->is_currently_working;
            $work_experince->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function experience_list(Request $request)
    {
        try {
            $experience_list = UserWorkExperience::where('user_id', auth('sanctum')->user()->id)->get()->makeHidden('user_id');
            return response()->json(['status' => 1, 'message' => 'Success', 'experience_list' => $experience_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function delete_experience(Request $request)
    {
        try {
            $deleteExperiences = UserWorkExperience::where('id', $request->id)->delete();
            if (empty($deleteExperiences)) {
                return response()->json(['status' => 0, 'message' => 'Invalid ID'], 200);
            }
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_total_experience(Request $request)
    {
        $request->validate([
            'experience_type' => 'required|in:1,2,3',
            'exp_years' => 'required',
            'exp_months' => 'required',
        ]);
        if ($request->hasFile('resume')) {
            $request->validate([
                'resume' => 'image|max:5096|mimes:png,jpg,jpeg,pdf'
            ]);
        }
        try {
            $total_experience = UserOtherInfo::where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($total_experience)) {
                $total_experience = new UserOtherInfo();
                $total_experience->user_id = auth('sanctum')->user()->id;
            }
            $total_experience->experience_type = $request->experience_type;
            $total_experience->exp_years = $request->exp_years;
            $total_experience->exp_months = $request->exp_months;
            if ($request->has('resume')) {
                $resume = Resumes::where('user_id', auth('sanctum')->user()->id)->first();
                if (empty($resume)) {
                    $resume = new Resumes();
                    $resume->user_id = auth('sanctum')->user()->id;
                }
                $fileName = $this->uploadResume($request->file('resume'));
                $this->deleteResumeIfExists($resume->image);
                $resume->image = $fileName;
                $resume->save();
            }
            $total_experience->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_education(Request $request)
    {
        $request->validate([
            'education_id' => 'required',
        ]);
        try {
            $education = UserOtherInfo::where('user_id', auth('sanctum')->user()->id)->first();
            if (empty($education)) {
                $education = new UserOtherInfo();
                $education->user_id = auth('sanctum')->user()->id;
            }
            $education->education_id = $request->education_id;
            $education->save();
            $about_user = User::find(auth('sanctum')->user()->id);
            $about_user->about = $request->about;
            $about_user->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function add_company_location(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'title' => 'required',
            'address' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
            'pincode' => 'required',
        ]);
        try {
            $location = new Locations();
            $location->user_id = auth('sanctum')->user()->id;
            $location->title = $request->title;
            $location->address = $request->address;
            $location->state_id = $request->state_id;
            $location->city_id = $request->city_id;
            $location->area_id = $request->area_id;
            $location->pincode = $request->pincode;
            $location->url = $request->url ?? '';
            $location->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_company_location(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $location = Locations::find($request->id);
            if (empty($location)) {
                return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
            }
            return response()->json(['status' => 1, 'message' => 'Success', 'location' => $location], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function update_company_location(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        $request->validate([
            'title' => 'required',
            'address' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
            'pincode' => 'required',
        ]);
        try {
            $location = Locations::find($request->id);
            if (empty($location)) {
                return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
            }
            $location->title = $request->title;
            $location->address = $request->address;
            $location->state_id = $request->state_id;
            $location->city_id = $request->city_id;
            $location->area_id = $request->area_id;
            $location->pincode = $request->pincode;
            $location->url = $request->url ?? '';
            $location->save();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function delete_company_location(Request $request)
    {
        if (auth('sanctum')->user()->type != 3) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $location = Locations::find($request->id);
            if (empty($location)) {
                return response()->json(['status' => 0, 'message' => 'Invalid Request'], 200);
            }
            $location->delete();
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function edit_about_us(Request $request)
    {
        $request->validate(['about' => 'required']);
        try {
            User::where('id', auth('sanctum')->user()->id)->update(['about' => $request->about]);
            return response()->json(['status' => 1, 'message' => 'Success'], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function switch_profile(Request $request)
    {
        if (!in_array(auth('sanctum')->user()->type, [3, 4])) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        DB::beginTransaction();
        try {
            $user = auth('sanctum')->user();

            $requiredCoins = settingsdata()->profile_switch_coins;
            $check_coins = userCoins($user->id) - $requiredCoins;
            if ($check_coins < 0) {
                return response()->json(['status' => 3, 'message' => 'Insufficient Coins'], 200);
            }

            // Deduct coins
            $deductResult = deductCoins($user->id, $requiredCoins);
            if (!$deductResult) {
                throw new \Exception('Unable to deduct the coins!');
            }

            // Switch profile
            $user->type = $user->type == 4 ? 3 : 4;
            $user->save();

            Jobs::where('user_id', $user->id)->update(['status' => 6]);
            JobApplications::where('user_id', $user->id)->whereNotNull('job_id')->update(['status' => 2]);

            // New Entry
            $tr = new Transactions();
            $tr->type = $user->type == 4 ? 8 : 7;
            $tr->user_id = $user->id;
            $tr->final_coins = $requiredCoins;
            $tr->save();
            DB::commit();
            return response()->json(['status' => 1, 'message' => "Success", 'profile_complete' => (new AuthenticationController)->isProfileComplete($user), 'type' => $user->type], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
    private function uploadProfileImage($file)
    {
        $imageName = 'profile-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(storage_path('app/public/admin/assets/images/profile/'), $imageName);
        return $imageName;
    }
    private function deleteProfileImageIfExists($imageName)
    {
        $imagePath = storage_path('app/public/admin/assets/images/profile/' . $imageName);
        if ($imageName != 'default.png' && file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
    }
    private function uploadProofImage($file)
    {
        $imageName = 'proof-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(storage_path('app/public/admin/assets/images/proofs/'), $imageName);
        return $imageName;
    }
    private function deleteProofImageIfExists($imageName)
    {
        $imagePath = storage_path('app/public/admin/assets/images/proofs/' . $imageName);
        if ($imageName != 'default.png' && file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
    }
    private function uploadResume($file)
    {
        $fileName = 'resume-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(storage_path('app/public/admin/assets/images/resumes/'), $fileName);
        return $fileName;
    }
    private function deleteResumeIfExists($fileName)
    {
        $imagePath = storage_path('app/public/admin/assets/images/resumes/' . $fileName);
        if ($fileName != 'default.png' && file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
    }
}
