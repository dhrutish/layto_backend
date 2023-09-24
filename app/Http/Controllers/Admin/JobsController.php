<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplications;
use App\Models\Jobs;
use App\Models\Feedbacks;
use App\Models\Transactions;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class JobsController extends Controller
{
    public $is_female_required = false;
    protected $offset;
    protected $limit;
    protected $order;
    protected $sort;
    public function __construct(Request $request)
    {
        $this->is_female_required = false;
        $this->offset = $request->filled('offset') ? $request->offset : 0;
        $this->limit = $request->filled('limit') ? $request->limit : 10;
        $this->order = $request->filled('order') ? $request->order : 'DESC';
        $this->sort = $request->filled('sort') ? $request->sort : 'id';
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $this->is_female_required = false;
            $data = $this->fetchJobs($request);
            return response()->json($data);
        }
        return view('admin.jobs.index');
    }

    function female_security(Request $request)
    {
        if ($request->ajax()) {
            $this->is_female_required = true;
            $data = $this->fetchJobs($request);
            return response()->json($data);
        }
        return view('admin.jobs.index');
    }

    function fetchJobs($request)
    {
        $sql = Jobs::with(['user', 'industry_types', 'payment_types', 'education'])->orderBy($this->sort, $this->order);
        if ($request->filled('search')) {
            $sql = $sql->where('title', 'LIKE', "%$request->search%")->orWhere('description', 'LIKE', "%$request->search%");
        }
        if ($this->is_female_required) {
            $sql = $sql->where('is_female_required', 1);
        }
        $total = $sql->count();
        if ($request->filled('limit')) {
            $sql =  $sql->skip($this->offset)->take($this->limit);
        }
        $res = $sql->get();
        $bulkData['rows'] = [];
        $bulkData['total'] = $total;
        $cnt = 1;
        foreach ($res as $key => $row) {

            $statusaction = '';
            $aj = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('jobs.status') . chr(39) . ')" href="javascript:;">' . ($row->is_female_required == 1 ? trans('labels.approve_female_security') : trans('labels.make_available')) . '</a></li>';
            $cj = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('jobs.status') . chr(39) . ')" href="javascript:;">' . trans('labels.close_job') . '</a></li>';
            if ($row->status == 2) {
                $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.closed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-ban"></i></span> </span>';
                $statusaction .= $aj;
            } else if ($row->status == 3) {
                $statusaction .= $aj;
                $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.job_spamed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa-regular fa-triangle-exclamation"></i></span> </span>';
            } else if ($row->status == 4) {
                $status = '<span class="badge badge-layto fs--2 badge-layto-info"> <span class="badge-label">' . trans('labels.auto_closed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                $statusaction .= $aj;
            } else if ($row->status == 6) {
                $status = '<span class="badge badge-layto fs--2 badge-layto-info"> <span class="badge-label">' . trans('labels.switch_profile_closed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                $statusaction .= $aj;
            } else if ($row->status == 5) {
                $status = '<span class="badge badge-layto fs--2 badge-layto-info"> <span class="badge-label">' . trans('labels.pending_verification') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-clock"></i></span> </span>';
                $statusaction .= $aj;
                $statusaction .= $cj;
            } else {
                $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.active') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                $statusaction .= $cj;
            }
            $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu">' . $statusaction . '<li><a class="dropdown-item" href="' . route('jobs.show', [$row->id]) . '">' . trans('labels.show_details') . '</a></li></ul>';
            $bulkData['rows'][] = [
                'id' => $cnt++,
                'provider_info' => '<a class="" href="' . URL::to('/job-providers/' . $row->user->id) . '">' . $row->user->name . '</a>',
                'title' => $row->title,
                'min_salary' => currency_formated($row->min_salary),
                'max_salary' => currency_formated($row->max_salary),
                'candidates' => $row->candidates,
                'industry_type' => $row->industry_types ? $row->industry_types->title_en : '',
                'payment_type' => $row->payment_types ? $row->payment_types->title_en : '',
                'education' => $row->education ? $row->education->title_en : '',
                'created_at' => date_time_formated($row->created_at),
                'reposted_on' => $row->is_reposted == 1 ? date_time_formated($row->reposted_on) : '',
                'status' => $status,
                'action' => $action,
            ];
        }
        return $bulkData;
    }
    public function show(string $id, Request $request)
    {
        $jobdata = Jobs::with(['user', 'category.category', 'skills.skill', 'industry_types', 'payment_types', 'education'])->where('id', $id)->first();
        abort_if(empty($jobdata), 404);
        if ($request->ajax()) {
            $bulkData['rows'] = [];
            if ($request->type == 1) {
                $sql = JobApplications::where('job_id', $jobdata->id)->orderBy($this->sort, $this->order);
                if ($request->filled('search')) {
                    $sql = $sql->where('name', 'LIKE', "%$request->search%")->orWhere('email', 'LIKE', "%$request->search%")->orWhere('mobile', 'LIKE', "%$request->search%");
                }
                $total = $sql->count();
                if ($request->filled('limit')) {
                    $sql =  $sql->skip($this->offset)->take($this->limit);
                }
                $res = $sql->get();
                $bulkData['total'] = $total;
                $cnt = 1;
                foreach ($res as $key => $row) {
                    if ($row->status == 2) {
                        $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.accepted') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                    } elseif ($row->status == 3) {
                        $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.rejected') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                    } else {
                        $status = '<span class="badge badge-layto fs--2 badge-layto-secondary"> <span class="badge-label">' . trans('labels.pending') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-clock"></i></span> </span>';
                    }
                    $bulkData['rows'][] = [
                        'id' => $cnt++,
                        'seeker_info' => $row->seeker->name,
                        'from_amount' =>  currency_formated($row->from_amount),
                        'to_amount' =>  currency_formated($row->to_amount),
                        'description' =>  Str::limit($row->description, 100),
                        'status' =>  $status,
                    ];
                }
            }
            if ($request->type == 2) {
                $sql = Feedbacks::where('job_id', $jobdata->id)->orderBy($this->sort, $this->order);
                if ($request->filled('search')) {
                    $sql = $sql->where('rating', 'LIKE', "%$request->search%")->orWhere('comment', 'LIKE', "%$request->search%");
                }
                $total = $sql->count();
                if ($request->filled('limit')) {
                    $sql =  $sql->skip($this->offset)->take($this->limit);
                }
                $res = $sql->get();
                $bulkData['total'] = $total;
                $cnt = 1;
                foreach ($res as $key => $row) {
                    if ($row->status == 2) {
                        $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.accepted') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                    } elseif ($row->status == 3) {
                        $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.rejected') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                    } else {
                        $status = '<span class="badge badge-layto fs--2 badge-layto-secondary"> <span class="badge-label">' . trans('labels.pending') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-clock"></i></span> </span>';
                    }
                    $html = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $html .= '<i class="far fa-star ' . ($row->rating >= $i ? 'text-warning' : 'text-muted') . '"></i>';
                    }
                    $bulkData['rows'][] = [
                        'id' => $cnt++,
                        'seeker_info' => $row->seeker_info->name,
                        'rating' => $html,
                        'comment' =>  $row->comment ?? '-',
                        'created_at' => date_time_formated($row->created_at),
                        'status' =>  $status,
                    ];
                }
            }
            return response()->json($bulkData);
        }
        return view('admin.jobs.show', compact('jobdata'));
    }
    public function status(Request $request)
    {
        $checkjob = Jobs::where('id', $request->id)->first();
        if (empty($checkjob)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_job')], 200);
        }
        DB::beginTransaction();
        try {
            $checkjob->status = $request->status;
            $checkjob->save();
            if ($request->status == 3) {
                $getusers = JobApplications::where('job_id', $checkjob->id)->get()->pluck('user_id')->toArray();
                foreach ($getusers as $key => $user_id) {
                    $tr_info = Transactions::select('id', 'user_id', 'final_coins')->where('user_id', $user_id)->where('job_id', $checkjob->id)->where('type', 5)->first();
                    $tr = new Transactions();
                    $tr->type = 11;
                    $tr->user_id = $user_id;
                    $tr->final_coins = $tr_info->final_coins;
                    $tr->coin_expire_days = settingsdata()->coin_expire_days;
                    $tr->save();
                    $getuser = User::where('id', $tr_info->id)->typeseeker()->available()->first();
                    if($getuser){
                        $title = 'Coins returned';
                        $description = 'Coins were returned because the job was spammed.';
                        send_notification([$getuser->token], $title, $description);
                        store_notification($user_id, $checkjob->id, $provider_id = null, $title, $description, 3);
                    }
                }
            }
            DB::commit();
            $table = str_replace('-', '', basename(url()->previous()));
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('femalesecurity', 'jobs', $table)], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
}
