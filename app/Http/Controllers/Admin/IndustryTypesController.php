<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\IndustryTypes;
use App\Models\UserOtherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IndustryTypesController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $offset = $req->filled('offset') ? $req->offset : 0;
            $limit = $req->filled('limit') ? $req->limit : 10;
            $order = $req->filled('order') ? $req->order : 'DESC';
            $sort = $req->filled('sort') ? $req->sort : 'id';
            $sql = IndustryTypes::orderBy($sort, $order);
            if ($req->filled('search')) {
                $sql = $sql->where('title_en', 'LIKE', "%$req->search%")->orWhere('title_hi', 'LIKE', "%$req->search%")->orWhere('title_gj', 'LIKE', "%$req->search%");
            }
            $total = $sql->count();
            if ($req->filled('limit')) {
                $sql =  $sql->skip($offset)->take($limit);
            }
            $res = $sql->get();
            $bulkData['rows'] = [];
            $bulkData['total'] = $total;
            $cnt = 1;
            foreach ($res as $key => $row) {
                if ($row->is_available == 1) {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('industry-types.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('industry-types.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#industrytypesmodal" data-itid="' . $row->id . '" data-title-en="' . $row->title_en . '" data-title-hi="' . $row->title_hi . '" data-title-gj="' . $row->title_gj . '" href="javascript:;">' . trans('labels.edit') . '</a></li>'.$statusaction.'</ul>';
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
        return view('admin.industrytypes.index');
    }
    public function store(Request $req)
    {
        if ($req->filled('industrytypesid')) {
            $ca = IndustryTypes::where('id', $req->industrytypesid)->first();
            if (empty($ca)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_industrytypes')], 200);
            }
        }
        $validator = Validator::make($req->all(), [
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
            if (!$req->filled('industrytypesid')) {
                $ca = new IndustryTypes();
            }
            $ca->title_en = $req->title_en;
            $ca->title_hi = $req->title_hi;
            $ca->title_gj = $req->title_gj;
            $ca->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $cu = IndustryTypes::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        if (empty($cu)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            $cu->is_available = $request->status;
            $cu->save();
            if ($request->status == 2) {
                Categories::where('industry_types_id',$cu->id)->update(['is_available' => 2]);
                UserOtherInfo::where('industry_types_id',$cu->id)->update(['industry_types_id' => null]);
            }
            Db::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            Db::rollBack();
            return errorResponse($th->getMessage());
        }
    }
}
