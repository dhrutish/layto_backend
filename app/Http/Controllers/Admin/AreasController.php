<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Areas;
use App\Models\Cities;
use App\Models\Locations;
use App\Models\States;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AreasController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $type = $request->filled('type') ? $request->type : 1;
            $sql = Areas::where('type', $type)->orderBy($sort, $order);
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
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('areas.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('areas.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                if ($row->type == 2) {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',0,' . chr(39) . route('areas.type') . chr(39) . ')" href="javascript:;">' . trans('labels.approve_request') . '</a></li>';
                    $statusaction .= '<li><a class="dropdown-item replace_with" data-bs-toggle="modal" data-bs-target="#replacemodal" data-aid="' . $row->id . '" data-next="' . route('areas.type') . '" href="javascript:;">' . trans('labels.replace_with') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#areamodal" data-aid="' . $row->id . '" data-sid="' . $row->state_id . '" data-cid="' . $row->city_id . '" data-title-en="' . $row->title_en . '" data-title-hi="' . $row->title_hi . '" data-title-gj="' . $row->title_gj . '" href="javascript:;">' . trans('labels.edit') . '</a></li>' . $statusaction . '</ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'state' => $row->state->title_en,
                    'city' => $row->city->title_en,
                    'title_en' => $row->title_en,
                    'title_hi' => $row->title_hi,
                    'title_gj' => $row->title_gj,
                    'status' => status_badge($row->is_available),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        $states = States::select('id', 'title_en')->latest()->get();
        $areas = Areas::select('id','title_en')->default()->available()->latest()->get();
        return view('admin.areas.index', compact('states','areas'));
    }
    public function getcities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'bail|required|exists:states,id',
        ], [
            '*.required' => trans('messages.field_required'),
            'state.exists' => trans('messages.invalid_state'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            $cities = Cities::select('id', 'title_en')->where('state_id', $request->state)->latest()->get();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'cities' => $cities], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function store(Request $request)
    {
        if ($request->filled('areaid')) {
            $ca = Areas::where('id', $request->areaid)->first();
            if (empty($ca)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_area')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'state' => 'bail|required|exists:states,id',
            'city' => 'bail|required|exists:cities,id',
            'title_en' => 'bail|required',
            'title_hi' => 'bail|required',
            'title_gj' => 'bail|required',
        ], [
            '*.required' => trans('messages.field_required'),
            'state.exists' => trans('messages.invalid_state'),
            'city.exists' => trans('messages.invalid_city'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('areaid')) {
                $ca = new Areas();
            }
            $ca->state_id = $request->state;
            $ca->city_id = $request->city;
            $ca->title_en = $request->title_en;
            $ca->title_hi = $request->title_hi;
            $ca->title_gj = $request->title_gj;
            $ca->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'cnt' => otherAreasCount()], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $cu = Areas::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        if (empty($cu)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        $checkcity = Cities::where('id', $cu->city_id)->where('is_available', 1)->first();
        $checkstate = States::where('id', $cu->state_id)->where('is_available', 1)->first();
        if (empty($checkcity) || empty($checkstate)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        try {
            $cu->is_available = $request->status;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous())), 'cnt' => otherAreasCount()], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function changetype(Request $request)
    {
        $checkdata = Areas::where('id', $request->id)->where('type', 2)->first();
        if (empty($checkdata)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            if ($request->has('type') || $request->filled('area')) {
                $validator = Validator::make($request->all(), [
                    'area' => 'bail|required|exists:areas,id',
                ], [
                    '*.required' => trans('messages.field_required'),
                    'area.exists' => trans('messages.invalid_area'),
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
                }
                $checkskill = Areas::where('id', $request->area)->default()->available()->first();
                if (empty($checkskill)) {
                    return response()->json(['status' => 0, 'message' => trans('messages.invalid_area')], 200);
                }
                Locations::where('area_id', $checkdata->id)->update(['area_id' => $checkskill->id]);
                $checkdata->delete();
            } else {
                $checkdata->type = 1;
                $checkdata->save();
            }
            Db::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous())), 'cnt' => otherAreasCount()], 200);
        } catch (\Throwable $th) {
            Db::rollBack();
            return errorResponse($th->getMessage());
        }
    }
}
