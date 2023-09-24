<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Areas;
use App\Models\Cities;
use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $sql = States::orderBy($sort, $order);
            if ($request->filled('search')) {
                $sql = $sql->where('title_en', 'LIKE', "%$request->search%")->orWhere('title_hi', 'LIKE', "%$request->search%")->orWhere('title_gj', 'LIKE', "%$request->search%");
            }
            $total = $sql->count();
            if ($request->filled('limit')) {
                $sql =  $sql->skip($offset)->take($limit);
            }
            $res = $sql->get();
            $bulkData['rows'] = [];
            $bulkData['total'] = $total;
            $cnt = 1;
            foreach ($res as $key => $row) {

                if ($row->is_available == 1) {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('states.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('states.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#statemodal" data-sid="' . $row->id . '" data-title-en="' . $row->title_en . '" data-title-hi="' . $row->title_hi . '" data-title-gj="' . $row->title_gj . '" href="javascript:;">' . trans('labels.edit') . '</a></li>'.$statusaction.'</ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'title_en' => $row->title_en,
                    'title_hi' => $row->title_hi,
                    'title_gj' => $row->title_gj,
                    'status' => status_badge($row->is_available),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.states.index');
    }
    public function store(Request $request)
    {
        if ($request->filled('stateid')) {
            $cu = States::where('id', $request->stateid)->first();
            if (empty($cu)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_state')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'title_en' => 'bail|required',
            'title_hi' => 'bail|required',
            'title_gj' => 'bail|required',
        ], [
            '*.required' => trans('messages.field_required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('stateid')) {
                $cu = new States();
            }
            $cu->title_en = $request->title_en;
            $cu->title_hi = $request->title_hi;
            $cu->title_gj = $request->title_gj;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $cu = States::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        if (empty($cu)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            $cu->is_available = $request->status;
            $cu->save();
            if ($request->status == 2) {
                Cities::where('state_id',$cu->id)->update(['is_available' => 2]);
                Areas::where('state_id',$cu->id)->update(['is_available' => 2]);
            }
            Db::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            Db::rollBack();
            return errorResponse($th->getMessage());
        }
    }

}
