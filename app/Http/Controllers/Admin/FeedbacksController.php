<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedbacks;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeedbacksController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $type = $request->filled('type') ? $request->type : 1;

            $sql = Feedbacks::orderBy($sort, $order);
            if ($type == 2) {
                $sql = $sql->where('is_dispute_created', 1);
            }
            if ($request->filled('search')) {
                $sql = $sql->where('rating', 'LIKE', "%$request->search%")->orWhere('comment', 'LIKE', "%$request->search%");
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
                $statusaction = '';
                if ($row->is_dispute_created == 1) {
                    $statusaction = '';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item show-details" data-bs-toggle="modal" data-bs-target="#feedbackmodal" data-fid="' . $row->id . '" href="javascript:;">' . trans('labels.show_details') . '</a></li>' . $statusaction . '</ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'provider_info' => !empty($row->provider_info) ? $row->provider_info->name : '-',
                    'job_info' => !empty($row->job_info) ? $row->job_info->title : '-',
                    'seeker_info' => !empty($row->seeker_info) ? $row->seeker_info->name : '-',
                    'rating' => '<i class="far fa-star text-warning"></i> ' . $row->rating,
                    'comment' => Str::limit($row->comment, 100),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.feedbacks.index');
    }
    public function show(string $id)
    {
        try {
            $data = Feedbacks::find($id);
            $html = view('admin.feedbacks.modalcontent', compact('data'))->render();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'html' => $html], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $checkdata = Feedbacks::where('id', $request->id)->where('is_dispute_created', 1)->first();
        if (empty($checkdata)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        DB::beginTransaction();
        try {
            $checkdata->dispute_status = $request->status;
            $checkdata->save();
            $checkdata = Feedbacks::find($request->id);
            if ($request->status == 2 && $checkdata->job_id != "") {
                Jobs::where('id', $checkdata->job_id)->update(['status' => 2]);
            }
            DB::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return errorResponse($th->getMessage());
        }
    }
}
