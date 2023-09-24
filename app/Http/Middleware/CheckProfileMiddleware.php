<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('sanctum')->user()->load('locations', 'other_info', 'categories', 'skills', 'work_experience');
        $errorMessages = [
            'name' => 'Username is empty.',
            'mobile' => 'Mobile number is empty.',
            'locations' => 'No locations are added.',
            'id_proof_details' => 'ID proof details are empty.',
            'gender' => 'No gender is selected.',
            'industry_types_id' => 'No industry type are selected.',
            'categories' => 'No categories are selected.',
            'skills' => 'No skills are selected.',
            'availabilities_id' => 'No availability is selected.',
            'exp_salary_from' => 'Minimum salary is empty.',
            'exp_salary_to' => 'Maximum salary is empty.',
            'work_experience' => 'No work experience is added.'
        ];

        $missingFields = [];
        if (!$user || empty($user->name) || empty($user->mobile) || $user->locations->count() == 0 || empty($user->id_proof_details)) {
            if (empty($user->name)) {
                $missingFields[] = 'name';
            }
            if (empty($user->mobile)) {
                $missingFields[] = 'mobile';
            }
            if ($user->locations->count() == 0) {
                $missingFields[] = 'locations';
            }
            if (empty($user->id_proof_details)) {
                $missingFields[] = 'id_proof_details';
            }
        }

        if ($user->type == 4) {
            $requiredFields = ['gender', 'industry_types_id', 'categories', 'skills', 'availabilities_id', 'exp_salary_from', 'exp_salary_to'];
            foreach ($requiredFields as $field) {
                if (in_array($field, ['categories', 'skills','locations'])) {
                    if (count($user->$field) == 0) {
                        $missingFields[] = $field;
                    }
                } else {
                    if (empty($user->other_info->$field)) {
                        $missingFields[] = $field;
                    }
                }
            }
            if ($user->other_info && $user->other_info->experience_type == 3 && count($user->work_experience) == 0) {
                $missingFields[] = 'work_experience';
            }
        }

        $errorMessagesForMissingFields = array_map(function ($field) use ($errorMessages) {
            return $errorMessages[$field];
        }, $missingFields);

        if (!empty($errorMessagesForMissingFields)) {
            throw new HttpResponseException(response()->json(['status' => 2, 'message' => $errorMessagesForMissingFields], 200));
        }


        return $next($request);
    }
}
