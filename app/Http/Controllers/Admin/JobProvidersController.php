<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IdProof;
use App\Models\Jobs;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class JobProvidersController extends Controller
{
    protected $offset;
    protected $limit;
    protected $order;
    protected $sort;
    public function __construct(Request $request)
    {
        $this->offset = $request->filled('offset') ? $request->offset : 0;
        $this->limit = $request->filled('limit') ? $request->limit : 10;
        $this->order = $request->filled('order') ? $request->order : 'DESC';
        $this->sort = $request->filled('sort') ? $request->sort : 'id';
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sql = User::where('type', 3)->orderBy($this->sort, $this->order);
            if ($request->filled('search')) {
                $sql = $sql->where('name', 'LIKE', "%$request->search%")->orWhere('email', 'LIKE', "%$request->search%")->orWhere('mobile', 'LIKE', "%$request->search%");
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

                if ($row->is_available == 1) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.available') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('job-proividers.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.not_available') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('job-proividers.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }

                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i>
                <ul class="dropdown-menu">' . $statusaction . '<li><a class="dropdown-item reset-password" data-uid="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#resetpassmodal" href="javascript:;">' . trans('labels.reset_password') . '</a></li><li><a class="dropdown-item" href="' . URL::to('/job-providers/' . $row->id) . '">' . trans('labels.show_details') . '</a></li></ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'profile' => '<img src="' . $row->image_url . '" alt="adveretising image"  class="rounded" height="50" width="50">',
                    'name' => $row->name,
                    'email' => $row->email,
                    'mobile' => '+' . $row->country_code . ' ' . $row->mobile,
                    'login_type' => '<img src="' . image_path('login' . $row->login_type . '.png') . '" alt="login type"> ' . $row->LoginTypeText,
                    'status' => $status,
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.users.jobproviders');
    }
    public function show(Request $request, string $id)
    {
        $udata = User::with('locations.states', 'locations.cities', 'locations.areas')->where('id', $id)->where('type', 3)->first();
        abort_if(empty($udata), 404);
        if ($request->ajax()) {
            if ($request->type == 1) {
                $sql = Jobs::with(['user', 'industry_types', 'payment_types', 'education'])->where('user_id', $udata->id)->orderBy($this->sort, $this->order);
                if ($request->filled('search')) {
                    $sql = $sql->where('title', 'LIKE', "%$request->search%")->orWhere('description', 'LIKE', "%$request->search%");
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
                        // 'provider_info' => '<a class="" href="' . URL::to('/job-providers/' . $row->user->id) . '">' . $row->user->name . '</a>',
                        'title' => $row->title,
                        'min_salary' => currency_formated($row->min_salary),
                        'max_salary' => currency_formated($row->max_salary),
                        'candidates' => $row->candidates,
                        'industry_type' => $row->industry_types ? $row->industry_types->title_en : '',
                        // 'payment_type' => $row->payment_types ? $row->payment_types->title_en : '',
                        // 'education' => $row->education ? $row->education->title_en : '',
                        'created_at' => date_time_formated($row->created_at),
                        // 'reposted_on' => $row->is_reposted == 1 ? date_time_formated($row->reposted_on) : '',
                        'status' => $status,
                        'action' => $action,
                    ];
                }

                return response()->json($bulkData);
            }
            if ($request->type == 2) {
                $sql = Transactions::orderBy($this->sort, $this->order)->where('user_id', $udata->id);
                if ($request->filled('search')) {
                    $sql = $sql->where('final_coins', 'LIKE', "%$request->search%")->orWhere('transaction_id', 'LIKE', "%$request->search%");
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
                    $days_left = '-';
                    if (in_array($row->type, [1, 2, 3, 6, 9, 11]) && !in_array($row->is_coins_used,[1,4])) {
                        $days_left = Carbon::parse($row->created_at)->addDays($row->coin_expire_days > 0 ? $row->coin_expire_days : 365)->diffInDays();
                    }
                    $bulkData['rows'][] = [
                        'id' => $cnt++,
                        'image' => '<img src="' . image_path(in_array($row->type, [1, 2, 3, 6, 9, 11]) ? 'in.png' : 'out.png') . '" alt="status image"  class="rounded" height="30" width="30">',
                        'coins' => $row->final_coins,
                        'description' => !empty($row->description) ? $row->description : transactionDescription()[$row->type - 1],
                        'created_at' => date_time_formated($row->created_at),
                        'days_left' => $days_left,
                    ];
                }
                return response()->json($bulkData);
            }
        }
        return view('admin.users.jobproviderdetails', compact('udata'));
    }

    public function status(Request $request)
    {
        $cu = User::where('id', $request->id)->where('type', 3)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        if (empty($cu)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_user')], 200);
        }
        try {
            $cu->is_available = $request->status;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
