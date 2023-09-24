<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\IndustryTypes;
use App\Models\JobSkills;
use App\Models\Skills;
use App\Models\UserSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    public function  index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $type = $request->filled('type') ? $request->type : 1;

            $sql = Skills::with('category')->where('type', $type)->orderBy($sort, $order);
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
                    $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.available') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('skills.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.not_available') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('skills.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                if ($row->type == 2) {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',0,' . chr(39) . route('skills.type') . chr(39) . ')" href="javascript:;">' . trans('labels.approve_request') . '</a></li>';
                    $statusaction .= '<li><a class="dropdown-item replace_with" data-bs-toggle="modal" data-bs-target="#replacemodal" data-sid="' . $row->id . '" data-next="' . route('skills.type') . '" href="javascript:;">' . trans('labels.replace_with') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#skillsmodal" data-cid="' . $row->categories_id . '" data-sid="' . $row->id . '" data-title-en="' . $row->title_en . '" data-title-hi="' . $row->title_hi . '" data-title-gj="' . $row->title_gj . '" href="javascript:;">' . trans('labels.edit') . '</a></li>' . $statusaction . '</ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'category' => $row->category->title_en,
                    'title_en' => $row->title_en,
                    'title_hi' => $row->title_hi,
                    'title_gj' => $row->title_gj,
                    'status' => status_badge($row->is_available),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        $itypes = IndustryTypes::with('categories')->orderByDesc('id')->get();
        $skills = Skills::select('id','title_en')->default()->available()->latest()->get();
        return view('admin.skills.index', compact('itypes', 'skills'));
    }
    public function store(Request $request)
    {
        if ($request->filled('skillsid')) {
            $ca = Skills::where('id', $request->skillsid)->first();
            if (empty($ca)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_categories')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'category' => 'bail|required|exists:categories,id',
            'title_en' => 'bail|required',
            'title_hi' => 'bail|required',
            'title_gj' => 'bail|required',
        ], [
            '*.required' => trans('messages.field_required'),
            'category.exists' => trans('messages.invalid_category'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('skillsid')) {
                $ca = new Skills();
            }
            $ca->categories_id = $request->category;
            $ca->title_en = $request->title_en;
            $ca->title_hi = $request->title_hi;
            $ca->title_gj = $request->title_gj;
            $ca->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $checkdata = Skills::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        $checkcategory = Categories::where('id', $checkdata->categories_id)->where('is_available', 1)->first();
        if (empty($checkdata) || empty($checkcategory)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            $checkdata->is_available = $request->status;
            $checkdata->save();
            if ($request->status == 2) {
                UserSkills::where('skills_id', $checkdata->id)->delete();
            }
            Db::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous())), 'cnt' => otherSkillsCount()], 200);
        } catch (\Throwable $th) {
            Db::rollBack();
            return errorResponse($th->getMessage());
        }
    }
    public function changetype(Request $request)
    {
        $checkdata = Skills::where('id', $request->id)->where('type', 2)->first();
        if (empty($checkdata)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            if ($request->has('type') || $request->filled('skill')) {
                $validator = Validator::make($request->all(), [
                    'skill' => 'bail|required|exists:skills,id',
                ], [
                    '*.required' => trans('messages.field_required'),
                    'skill.exists' => trans('messages.invalid_skill'),
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
                }
                $checkskill = Skills::where('id', $request->skill)->default()->available()->first();
                if (empty($checkskill)) {
                    return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
                }
                UserSkills::where('skills_id', $checkdata->id)->update(['skills_id' => $checkskill->id]);
                JobSkills::where('skills_id', $checkdata->id)->update(['skills_id' => $checkskill->id]);
                $checkdata->delete();
            } else {
                $checkdata->type = 1;
                $checkdata->save();
            }
            Db::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous())), 'cnt' => otherSkillsCount()], 200);
        } catch (\Throwable $th) {
            Db::rollBack();
            return errorResponse($th->getMessage());
        }
    }
}
