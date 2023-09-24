<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FAQController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $sql = FAQ::orderBy($sort, $order);
            if ($request->filled('search')) {
                $sql = $sql->where('title', 'LIKE', "%$request->search%")->orWhere('description', 'LIKE', "%$request->search%");
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
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('faqs.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('faqs.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#faqmodal" data-fid="' . $row->id . '" data-title="' . $row->title . '" data-description="' . $row->description . '" href="javascript:;">' . trans('labels.edit') . '</a></li>' . $statusaction . '<li><a class="dropdown-item text-danger" href="javascript:;" onclick="deletedata(' . chr(39) . route('faqs.destroy', [$row->id]) . chr(39) . ')">' . trans('labels.delete') . '</a></li></ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'title' => $row->title,
                    'description' => strip_tags(Str::limit($row->description, 150)),
                    'status' => status_badge($row->is_available),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.faqs.index');
    }
    public function store(Request $request)
    {
        if ($request->filled('faqid')) {
            $cu = FAQ::where('id', $request->faqid)->first();
            if (empty($cu)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_faq')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required',
            'description' => 'bail|required',
        ], [
            '*.required' => trans('messages.field_required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('faqid')) {
                $cu = new FAQ();
            }
            $cu->title = $request->title;
            $cu->description = $request->description;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => Str::limit($th->getMessage(), 200)], 200);
        }
    }
    public function destroy(string $id)
    {
        try {
            $cf = FAQ::find($id);
            if (!$cf) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
            }
            $cf->delete();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $checkdata = FAQ::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        if (empty($checkdata)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        try {
            $checkdata->is_available = $request->status;
            $checkdata->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
