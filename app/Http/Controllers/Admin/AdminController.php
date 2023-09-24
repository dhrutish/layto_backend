<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
    public function list(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 10;
        $order = $request->filled('order') ? $request->order : 'DESC';
        $sort = $request->filled('sort') ? $request->sort : 'id';
        $sql = User::where('type', 2)->orderBy($sort, $order);
        if ($request->filled('search')) {
            $sql = $sql->where('name', 'LIKE', "%$request->search%")->orWhere('email', 'LIKE', "%$request->search%")->orWhere('mobile', 'LIKE', "%$request->search%");
        }
        $total = $sql->count();
        if ($request->filled('limit')) {
            $sql =  $sql->skip($offset)->take($limit);
        }
        $res = $sql->get();
        $bulkData = [];
        $bulkData['rows'] = [];
        $bulkData['total'] = $total;
        $count = 1;
        foreach ($res as $row) {
            if ($row->is_available == 1) {
                $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('sub.admins.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
            } else {
                $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('sub.admins.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
            }
            $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#subadminmodal" data-uid="' . $row->id . '" data-name="' . $row->name . '" data-email="' . $row->email . '" data-mobile="' . $row->mobile . '" href="javascript:;">' . trans('labels.edit') . '</a></li>' . $statusaction . '</ul>';
            $bulkData['rows'][] = [
                'id' => $count,
                'profile' => '<img src="' . $row->image_url . '" alt="profile"  class="rounded" height="50" width="50">',
                'name' => $row->name,
                'email' => $row->email,
                'mobile' => $row->mobile,
                'status' => status_badge($row->is_available),
                'action' => $action,
            ];
            $count++;
        }
        return response()->json($bulkData);
    }
    public function store(Request $request)
    {
        if ($request->filled('userid')) {
            $cu = User::where('id', $request->userid)->where('type', 2)->where('is_available', 1)->first();
            if (empty($cu)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_user')], 200);
            }
            $v = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $cu->id,
                'mobile' => 'required|numeric|unique:users,mobile,' . $cu->id,
            ];
        } else {
            $v = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|numeric|unique:users,mobile',
                'password' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $v, [
            'name.required' => trans('messages.field_required'),
            'email.required' => trans('messages.field_required'),
            'email.email' => trans('messages.valid_email'),
            'email.unique' => trans('messages.email_exist'),
            'mobile.required' => trans('messages.field_required'),
            'mobile.numeric' => trans('messages.numeric_only'),
            'mobile.unique' => trans('messages.mobile_exist'),
            'password.required' => trans('messages.field_required'),
        ]);
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
            }
        }
        try {
            if (!$request->filled('userid')) {
                $cu = new User();
                $cu->password = Hash::make($request->password);
                $cu->type = 2;
                $cu->is_available = 1;
            }
            $cu->name = $request->name;
            $cu->email = $request->email;
            $cu->mobile = $request->mobile;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }

    public function status(Request $request)
    {
        $cu = User::where('id', $request->id)->where('type', 2)->where('is_available', $request->status == 2 ? 1 : 2)->first();
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
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('admin')->with('success', trans('messages.success'));
    }
}
