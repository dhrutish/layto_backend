<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpamRequests;
use App\Models\SpamRequestUsers;
use Illuminate\Http\Request;

class SpamRequestsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $sql = SpamRequests::orderBy($sort, $order);
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
                if ($row->job_info->status == 2) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.closed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-ban"></i></span> </span>';
                } else if ($row->job_info->status == 3) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.job_spamed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa-regular fa-triangle-exclamation"></i></span> </span>';
                } else if ($row->job_info->status == 4) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-info"> <span class="badge-label">' . trans('labels.auto_closed') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                } else if ($row->job_info->status == 5) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-info"> <span class="badge-label">' . trans('labels.pending_verification') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-clock"></i></span> </span>';
                } else {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.active') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item show-details" data-bs-toggle="modal" data-bs-target="#spammodal" data-rid="' . $row->id . '" href="javascript:;">' . trans('labels.show_details') . '</a></li></ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'job_info' => '<a href="'.route('jobs.show',[$row->job_info->id]).'" class="text-dark h5">'.$row->job_info->title.'</a>',
                    'status' => $status,
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.spamrequests.index');
    }
    public function show(string $id,Request $request)
    {
        try {
            $data = SpamRequests::find($id);
            if ($data) {
                $html = view('admin.spamrequests.modalcontent', compact('data'))->render();
                $offset = $request->filled('offset') ? $request->offset : 0;
                $limit = $request->filled('limit') ? $request->limit : 10;
                $order = $request->filled('order') ? $request->order : 'DESC';
                $sort = $request->filled('sort') ? $request->sort : 'id';
                $sql = SpamRequestUsers::where('spam_request_id',$data->id)->orderBy($sort, $order);
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
                    $bulkData['rows'][] = [
                        'id' => $cnt++,
                        'seeker_info' => $row->seeker_info->name,
                        'note' => $row->note,
                        'description' => $row->description,
                    ];
                }
                return response()->json(['status' => 1, 'message' => trans('messages.success'), 'html' => $html, 'tabledata' => $bulkData], 200);
            }
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
