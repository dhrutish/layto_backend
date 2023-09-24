<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherNotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $type = $request->filled('type') ? $request->type : 1;

            $sql = OtherNotes::where('type', $type)->orderBy($sort, $order);
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
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#notesmodal" data-nid="' . $row->id . '" data-type="' . $row->type . '" data-title-en="' . $row->title_en . '" data-title-hi="' . $row->title_hi . '" data-title-gj="' . $row->title_gj . '" href="javascript:;">' . trans('labels.edit') . '</a></li><li><a class="dropdown-item text-danger" href="javascript:;" onclick="deletedata('.chr(39).route('notes.destroy',[$row->id]).chr(39).')">' . trans('labels.delete') . '</a></li></ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'title_en' => $row->title_en,
                    'title_hi' => $row->title_hi,
                    'title_gj' => $row->title_gj,
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.notes.index');
    }
    public function store(Request $request)
    {
        if ($request->filled('notesid')) {
            $cu = OtherNotes::where('id', $request->notesid)->first();
            if (empty($cu)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'type' => 'bail|required|in:1,2',
            'title_en' => 'bail|required',
            'title_hi' => 'bail|required',
            'title_gj' => 'bail|required',
        ], [
            'type.required' => trans('messages.invalid_request'),
            'type.in' => trans('messages.invalid_request'),
            '*.required' => trans('messages.field_required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('notesid')) {
                $cu = new OtherNotes();
            }
            $cu->type = $request->type;
            $cu->title_en = $request->title_en;
            $cu->title_hi = $request->title_hi;
            $cu->title_gj = $request->title_gj;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function destroy(string $id)
    {
        try {
            $co = OtherNotes::find($id);
            if (!empty($co)) {
                $co->delete();
                return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
            }
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
