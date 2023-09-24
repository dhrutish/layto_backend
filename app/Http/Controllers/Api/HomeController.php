<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertising;
use App\Models\Jobs;
use App\Models\User;
use App\Http\Controllers\Api\AuthenticationController;
class HomeController extends Controller
{
    public function home_feeds()
    {
        try {
            $banners = Advertising::available()->get()->makeHidden('is_available');
            $jobs = Jobs::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'image');
                },
                'availability',
                'location_info',
                'location_info.states',
                'location_info.cities',
                'location_info.areas',
                'skills.skill',
                'favorites',
            ])
                ->select('id', 'user_id', 'title', 'availabilities_id', 'locations_id', 'min_salary', 'max_salary')
                ->orderByDesc('created_at')
                ->Available()
                ->PostedOn()
                ->get();
            $jobs->each(function ($job) {
                $job->is_job_favorite = $job->isJobFavorite();
            });
            $candidate_list = User::with([
                'other_info' => function ($query) {
                    $query->select('id', 'user_id', 'availabilities_id', 'exp_salary_from', 'exp_salary_to', 'exp_years', 'exp_months');
                },
                'other_info.availability' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'skills.skill' => function ($query) {
                    $query->select('id', 'title_en', 'title_hi', 'title_gj');
                },
                'current_working' => function ($query) {
                    $query->select('id', 'user_id', 'company_name', 'is_currently_working');
                },
            ])
                ->select('id', 'name', 'image')
                ->orderByDesc('created_at')
                ->typeseeker()
                ->available()
                ->get()
                ->each(function ($candidate) {
                    $candidate->is_user_favorites = $candidate->IsCandidateFavorite();
                    $candidate->profile_complete = (new AuthenticationController)->isProfileComplete($candidate->id);
                });
            return response()->json(['status' => 1, 'message' => 'Success', 'banners' => $banners, (auth('sanctum')->user()->type == 3) ? 'candidate_list' : 'jobs' => (auth('sanctum')->user()->type == 3) ? $candidate_list : $jobs], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function search_filter_seekers(Request $request)
    {
        if (!in_array(auth('sanctum')->user()->type, [3])) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $data = User::with([
                'other_info.availability', 'current_working', 'skills.skill', 'categories',
                'other_info' => function ($query) use ($request) {
                    $query->select('id', 'user_id', 'industry_types_id', 'availabilities_id', 'exp_salary_from', 'exp_salary_to', 'experience_type', 'exp_years', 'exp_months');
                },
            ])
                ->whereHas('other_info', function ($query) use ($request) {
                    if ($request->filled('salary_from')) {
                        $query->where('exp_salary_from', '>=', $request->salary_from);
                    }
                    if ($request->filled('salary_to')) {
                        $query->where('exp_salary_to', '<=', $request->salary_to);
                    }
                    if ($request->filled('industry_types_id')) {
                        $query->whereIn('industry_types_id', explode(',', $request->industry_types_id));
                    }
                    if ($request->filled('availabilities_id')) {
                        $query->whereIn('availabilities_id', explode(',', $request->availabilities_id));
                    }
                    if ($request->filled('experience_type')) {
                        $query->whereIn('experience_type', explode(',', $request->experience_type));
                        if ($request->filled('exp_from') && $request->filled('exp_to')) {
                            $query->where(function ($subQuery) use ($request) {
                                $subQuery->whereBetween('exp_years', [$request->exp_from, $request->exp_to]);
                            });
                        } else if ($request->filled('exp_from') && !$request->filled('exp_to')) {
                            $query->where(function ($subQuery) use ($request) {
                                $subQuery->where('exp_years', '>=', $request->exp_from);
                            });
                        }
                    }
                })
                ->when($request->type == 2, function ($query) use ($request) {
                    $query->FavoriteSeekers(auth('sanctum')->user()->id);
                })
                ->when($request->filled('categories_id'), function ($query) use ($request) {
                    $catIds = explode(',', $request->categories_id);
                    $query->whereHas('categories', function ($subQuery) use ($catIds) {
                        $subQuery->whereIn('categories_id', $catIds);
                    });
                })
                ->when($request->filled('skills_id'), function ($query) use ($request) {
                    $skillsIds = explode(',', $request->skills_id);
                    $query->whereHas('skills', function ($subQuery) use ($skillsIds) {
                        $subQuery->whereIn('skills_id', $skillsIds);
                    });
                })
                ->when($request->filled('search_keyword'), function ($query) use ($request) {
                    $keyword = $request->search_keyword;
                    $query->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('name', 'like', "%$keyword%")
                            ->orWhereHas('other_info.availability', function ($availabilityQuery) use ($keyword) {
                                $availabilityQuery->where('title_en', 'like', "%$keyword%")->orWhere('title_hi', 'like', "%$keyword%")->orWhere('title_gj', 'like', "%$keyword%");
                            })
                            ->orWhereHas('skills.skill', function ($skillQuery) use ($keyword) {
                                $skillQuery->where('title_en', 'like', "%$keyword%")->orWhere('title_hi', 'like', "%$keyword%")->orWhere('title_gj', 'like', "%$keyword%");
                            });
                    });
                })
                ->select('id', 'name', 'about', 'image')
                ->typeseeker()
                ->available()
                ->latest()
                ->get()
                ->each(function ($candidate) {
                    $candidate->is_user_favorites = $candidate->IsCandidateFavorite();
                    $candidate->profile_complete = (new AuthenticationController)->isProfileComplete($candidate->id);
                })->makeHidden(['categories', 'candidatefavorites']);
            return response()->json(['status' => 1, 'message' => 'Success', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function search_filter_jobs(Request $request)
    {
        if (auth('sanctum')->user()->type != 4) {
            return response()->json(['status' => 0, 'message' => 'Invalid User'], 200);
        }
        try {
            $sort_by = $request->sort_by;
            $columns = ['created_at', 'created_at', 'max_salary', 'min_salary'];
            $sortmethod = ['desc', 'asc', 'desc', 'asc'];
            if ($request->filled('type') && $request->type == 1) {
                $user = auth('sanctum')->user();
                $otherinfo = $user->other_info;
                $request['salary_from'] = $otherinfo->exp_salary_from;
                $request['salary_to'] = $otherinfo->exp_salary_to;
                $request['industry_types_id'] = $otherinfo->industry_types_id;
                $request['availabilities_id'] = $otherinfo->availabilities_id;
                $request['experience_type'] = $otherinfo->experience_type;
                $request['exp_from'] = $request['experience_type'] == 3 ? $otherinfo->exp_years : '';
                $request['skills_id'] = implode(',', $user->skills->pluck('skills_id')->toArray());
                $request['categories_id'] = implode(',', $user->categories->pluck('categories_id')->toArray());
            }
            $data = Jobs::with([
                'user' => function ($query) use ($request) {
                    $query->select('id', 'name','image');
                },
                'location_info' => function ($query) use ($request) {
                    $query->select('id', 'user_id', 'city_id', 'area_id');
                },
                'location_info.cities', 'location_info.areas', 'availability', 'skills.skill', 'categories',
            ])
                ->when($request->type == 2, function ($query) use ($request) {
                    $query->FavoriteJobs(auth('sanctum')->user()->id);
                })
                ->when($request->filled('salary_from'), function ($query) use ($request) {
                    $query->where('min_salary', '>=', $request->salary_from);
                })
                ->when($request->filled('salary_to'), function ($query) use ($request) {
                    $query->where('max_salary', '<=', $request->salary_to);
                })
                ->when($request->filled('industry_types_id'), function ($query) use ($request) {
                    $query->whereIn('industry_types_id', explode(',', $request->industry_types_id));
                })
                ->when($request->filled('payment_types_id'), function ($query) use ($request) {
                    $query->whereIn('payment_types_id', explode(',', $request->payment_types_id));
                })
                ->when($request->filled('availabilities_id'), function ($query) use ($request) {
                    $query->whereIn('availabilities_id', explode(',', $request->availabilities_id));
                })
                ->when($request->filled('experience_type'), function ($query) use ($request) {
                    $query->whereIn('experience_type', explode(',', $request->experience_type));
                    if ($request->filled('exp_from') && $request->filled('exp_to')) {
                        $query->where(function ($subQuery) use ($request) {
                            $subQuery->whereBetween('exp_years', [$request->exp_from, $request->exp_to]);
                        });
                    } else if ($request->filled('exp_from') && !$request->filled('exp_to')) {
                        $query->where(function ($subQuery) use ($request) {
                            $subQuery->where('exp_years', '>=', $request->exp_from);
                        });
                    }
                })
                ->when($request->filled('categories_id'), function ($query) use ($request) {
                    $skillsIds = explode(',', $request->categories_id);
                    $query->whereHas('categories', function ($subQuery) use ($skillsIds) {
                        $subQuery->whereIn('categories_id', $skillsIds);
                    });
                })
                ->when($request->filled('skills_id'), function ($query) use ($request) {
                    $skillsIds = explode(',', $request->skills_id);
                    $query->whereHas('skills', function ($subQuery) use ($skillsIds) {
                        $subQuery->whereIn('skills_id', $skillsIds);
                    });
                })
                ->when($request->filled('city_id'), function ($query) use ($request) {
                    $cityIds = explode(',', $request->city_id);
                    $query->whereHas('location_info', function ($subQuery) use ($cityIds) {
                        $subQuery->whereIn('city_id', $cityIds);
                    });
                })
                ->when($request->filled('search_keyword'), function ($query) use ($request) {
                    $keyword = $request->search_keyword;
                    $query->where(function ($subQuery) use ($keyword) {
                        $subQuery->where('title', 'like', "%$keyword%")
                            ->orWhereHas('user', function ($skillQuery) use ($keyword) {
                                $skillQuery->where('name', 'like', "%$keyword%");
                            })
                            ->orWhereHas('availability', function ($availabilityQuery) use ($keyword) {
                                $availabilityQuery->where('title_en', 'like', "%$keyword%")->orWhere('title_hi', 'like', "%$keyword%")->orWhere('title_gj', 'like', "%$keyword%");
                            })
                            ->orWhereHas('skills.skill', function ($skillQuery) use ($keyword) {
                                $skillQuery->where('title_en', 'like', "%$keyword%")->orWhere('title_hi', 'like', "%$keyword%")->orWhere('title_gj', 'like', "%$keyword%");
                            })
                            ->orWhereHas('location_info.cities', function ($skillQuery) use ($keyword) {
                                $skillQuery->where('title_en', 'like', "%$keyword%")->orWhere('title_hi', 'like', "%$keyword%")->orWhere('title_gj', 'like', "%$keyword%");
                            })
                            ->orWhereHas('location_info.areas', function ($skillQuery) use ($keyword) {
                                $skillQuery->where('title_en', 'like', "%$keyword%")->orWhere('title_hi', 'like', "%$keyword%")->orWhere('title_gj', 'like', "%$keyword%");
                            });
                    });
                })
                ->select('id', 'user_id', 'availabilities_id', 'industry_types_id', 'locations_id', 'payment_types_id', 'title', 'min_salary', 'max_salary', 'experience_type', 'exp_years')
                ->available()
                ->PostedOn()
                ->orderBy($columns[$sort_by - 1] ?? 'created_at', $sortmethod[$sort_by - 1] ?? 'desc')
                ->get()
                ->each(function ($candidate) {
                    $candidate->is_job_favorites = $candidate->IsJobFavorite();
                })->makeHidden(['categories', 'favorites']);
            return response()->json(['status' => 1, 'message' => 'Success', 'data' => $data], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }

    // public function filter(Request $request)
    // {
    //     $request->validate([
    //         'search_keyword' => 'required',
    //     ]);

    //     try {
    //         if (auth('sanctum')->user()->type == 3) {
    //             $get_filter_list = User::with([
    //                 'other_info' => function ($query) {
    //                     $query->select('id', 'user_id', 'availabilities_id', 'exp_salary_from', 'exp_salary_to', 'exp_years', 'exp_months');
    //                 },
    //                 'other_info.availability' => function ($query) {
    //                     $query->select('id', 'title_en', 'title_hi', 'title_gj');
    //                 },
    //                 'skills.skill' => function ($query) {
    //                     $query->select('id', 'title_en', 'title_hi', 'title_gj');
    //                 },
    //                 'current_working' => function ($query) {
    //                     $query->select('id', 'user_id', 'company_name', 'is_currently_working');
    //                 },
    //             ])
    //                 ->where('name', 'LIKE', '%' . $request->search_keyword . '%')
    //                 ->select('id', 'name', 'image')
    //                 ->orderByDesc('created_at')
    //                 ->typeseeker()
    //                 ->available()
    //                 ->get()
    //                 ->each(function ($candidate) {
    //                     $candidate->is_user_favorites = $candidate->IsCandidateFavorite();
    //                 });
    //         } else {
    //             $get_filter_list = Jobs::with([
    //                 'user' => function ($query) use ($request) {
    //                     $query->where('name', 'LIKE', '%' . $request->search_keyword . '%')->select('id', 'name', 'image');
    //                 },
    //                 'availability',
    //                 'location_info',
    //                 'location_info.states',
    //                 'location_info.cities',
    //                 'location_info.areas',
    //                 'skills.skill',
    //                 'favorites',
    //             ])
    //                 ->select('id', 'user_id', 'availabilities_id', 'locations_id', 'title', 'min_salary', 'max_salary')
    //                 ->orderByDesc('created_at')
    //                 ->PostedOn()
    //                 ->whereHas('user', function ($query) use ($request) {
    //                     $query->where('name', 'LIKE', '%' . $request->search_keyword . '%');
    //                 })
    //                 ->get()
    //                 ->each(function ($job) {
    //                     $job->is_job_favorite = $job->isJobFavorite();
    //                 });
    //         }

    //         return response()->json([
    //             'status' => 1,
    //             'message' => 'Success',
    //             'data' => $get_filter_list,
    //         ]);
    //     } catch (\Throwable $th) {
    //         return errorResponse($th->getMessage());
    //     }
    // }
}
