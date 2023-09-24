<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\IdProof;
use Illuminate\Http\Request;

class IdProofsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $type = $request->filled('type') ? $request->type : 1;

            $sql = IdProof::orderBy($sort, $order);
            if ($request->filled('search')) {
                $sql = $sql->where('title_en', 'LIKE', "%$request->search%")->orWhere('title_hi', 'LIKE', "%$request->search%")->orWhere('title_gj', 'LIKE', "%$request->search%");
            }
            if ($type == 2) {
                $sql = $sql->TypeSeeker();
            }else{
                $sql = $sql->TypeProvider();
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
                $a = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('proof.status') . chr(39) . ')" href="javascript:;">' . trans('labels.accept') . '</a></li>';
                $r = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',3,' . chr(39) . route('proof.status') . chr(39) . ')" href="javascript:;">' . trans('labels.reject') . '</a></li>';
                if ($row->status == 1) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-warning"> <span class="badge-label">' . trans('labels.pending') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-clock"></i></span> </span>';
                    $statusaction = $a.$r;
                } else if ($row->status == 2) {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-success"> <span class="badge-label">' . trans('labels.accepted') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-check"></i></span> </span>';
                    $statusaction = $r;
                } else {
                    $status = '<span class="badge badge-layto fs--2 badge-layto-danger"> <span class="badge-label">' . trans('labels.rejected') . '</span> <span class="ms-1 badge-icon-size"><i class="fa fa-close"></i></span> </span>';
                    $statusaction = $a;
                }
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'id_number' => $row->id_number,
                    'front_image' => '<img src="'.image_path($row->front_image).'" alt="FrontImage" class="rounded" height="50" with="50">',
                    'back_image' => '<img src="'.image_path($row->back_image).'" alt="BackIamge" class="rounded" height="50" with="50">',
                    'status' => $status,
                    'action' => '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu">'.$statusaction.'</ul>',
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.proofs.index');
    }
    public function status(Request $req)
    {
        $cp = IdProof::where('id', $req->id)->first();
        if (empty($cp)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        try {
            $cp->status = $req->status;
            $cp->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))  ], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
