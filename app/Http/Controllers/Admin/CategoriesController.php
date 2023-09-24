<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\IndustryTypes;
use App\Models\Skills;
use App\Models\UserCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $offset = $req->filled('offset') ? $req->offset : 0;
            $limit = $req->filled('limit') ? $req->limit : 10;
            $order = $req->filled('order') ? $req->order : 'DESC';
            $sort = $req->filled('sort') ? $req->sort : 'id';
            $sql = Categories::with('industry_type')->orderBy($sort, $order);
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
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('categories.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('categories.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#categoriesmodal" data-cid="' . $row->id . '" data-itid="' . $row->industry_types_id . '" data-title-en="' . $row->title_en . '" data-title-hi="' . $row->title_hi . '" data-title-gj="' . $row->title_gj . '" href="javascript:;">' . trans('labels.edit') . '</a></li>'.$statusaction.'</ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'industry' => $row->industry_type->title_en,
                    'title_en' => $row->title_en,
                    'title_hi' => $row->title_hi,
                    'title_gj' => $row->title_gj,
                    'status' => status_badge($row->is_available),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        $itypes = IndustryTypes::orderByDesc('id')->available()->get();
        return view('admin.categories.index',compact('itypes'));
    }
    public function store(Request $req)
    {
        if ($req->filled('categoriesid')) {
            $ca = Categories::where('id', $req->categoriesid)->first();
            if (empty($ca)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_categories')], 200);
            }
        }
        $validator = Validator::make($req->all(), [
            'industry' => 'bail|required|exists:industry_types,id',
            'title_en' => 'bail|required',
            'title_hi' => 'bail|required',
            'title_gj' => 'bail|required',
        ], [
            '*.required' => trans('messages.field_required'),
            'industry.exists' => trans('messages.invalid_industrytypes'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$req->filled('categoriesid')) {
                $ca = new Categories();
            }
            $ca->industry_types_id = $req->industry;
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
        $checkdata = Categories::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        $checkitype = IndustryTypes::where('id',@$checkdata->industry_types_id)->where('is_available',1)->first();
        if (empty($checkdata) || empty($checkitype)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            $checkdata->is_available = $request->status;
            $checkdata->save();
            if ($request->status == 2) {
                Skills::where('categories_id', $checkdata->id)->update(['is_available' => 2]);
                UserCategories::where('categories_id',$checkdata->id)->delete();
            }
            Db::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            Db::rollBack();
            return errorResponse($th->getMessage());
        }
    }
}
// DB::enableQueryLog();
// dd(DB::getQueryLog());

// $sql = Categories::join('industry_types', 'categories.industry_types_id', '=', 'industry_types.id')->orderBy('categories.' . $sort, $order)->select('categories.id', 'categories.industry_types_id', 'categories.title_en', 'categories.title_hi', 'categories.title_gj');
// if ($req->filled('search')) {
//     $keyword = $req->search;
//     $sql = $sql->where(function ($query) use ($keyword) {
//         $query->where('categories.title_en', 'LIKE', '%' . $keyword . '%')->orWhere('categories.title_hi', 'LIKE', '%' . $keyword . '%')->orWhere('categories.title_gj', 'LIKE', '%' . $keyword . '%')->orWhere('industry_types.title_en', 'LIKE', '%' . $keyword . '%')->orWhere('industry_types.title_hi', 'LIKE', '%' . $keyword . '%')->orWhere('industry_types.title_gj', 'LIKE', '%' . $keyword . '%');
//     });
// }
