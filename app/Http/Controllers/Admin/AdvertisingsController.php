<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertising;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisingsController extends Controller
{
    public function index(Request $req)
    {
        if ($req->ajax()) {
            $offset = $req->filled('offset') ? $req->offset : 0;
            $limit = $req->filled('limit') ? $req->limit : 10;
            $order = $req->filled('order') ? $req->order : 'DESC';
            $sort = $req->filled('sort') ? $req->sort : 'id';
            $sql = Advertising::orderBy($sort, $order);
            if ($req->filled('search')) {
                $sql = $sql->where('url', 'LIKE', "%$req->search%")->orWhere('expiry_date', 'LIKE', "%$req->search%")->orWhere('title', 'LIKE', "%$req->search%");
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
                $filetype = in_array(explode('.', $row->file)[1], ['gif', 'png', 'jpeg', 'jpg']) ? 1 : 2;
                if ($row->is_available == 1) {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',2,' . chr(39) . route('advertisings.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_unavailable') . '</a></li>';
                } else {
                    $statusaction = '<li><a class="dropdown-item" onclick="changestatus(' . $row->id . ',1,' . chr(39) . route('advertisings.status') . chr(39) . ')" href="javascript:;">' . trans('labels.make_available') . '</a></li>';
                }
                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#advertisingsmodal" data-aid="' . $row->id . '" data-add-title="' . $row->title . '" data-file-type="' . $filetype . '" data-file-url="' . $row->file_url . '" data-url="' . $row->url . '" data-expiry-date="' . $row->expiry_date . '" href="javascript:;">' . trans('labels.edit') . '</a></li>' . $statusaction . '<li><a class="dropdown-item text-danger" href="javascript:;" onclick="deletedata(' . chr(39) . route('advertisings.destroy', [$row->id]) . chr(39) . ')">' . trans('labels.delete') . '</a></li></ul>';
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'file' => $filetype == 2 ? '<a href="' . $row->file_url . '" target="_blank">Click here</a>' : '<img src="' . $row->file_url . '" alt="adveretising image"  class="rounded" height="50" width="50">',
                    'url' => '<a href="' . $row->url . '" target="_blank">Click here</a>',
                    'title' => $row->title,
                    'expiry_date' => date_time_formated($row->expiry_date),
                    'status' => status_badge($row->is_available),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.advertisings.index');
    }
    public function store(Request $req)
    {
        if ($req->filled('advertisingsid')) {
            $ca = Advertising::where('id', $req->advertisingsid)->first();
            if (empty($ca)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_advertisings')], 200);
            }
        }
        $validator = Validator::make($req->all(), [
            'title' => 'bail|required',
            'url' => 'bail|required',
            'expiry_date' => 'bail|required|date',
            'afile' => $req->filled('advertisingsid') ? 'mimes:jpg,jpeg,png,gif,mp4|max:20480' : 'bail|required|mimes:jpg,jpeg,png,gif,mp4',
        ], [
            '*.required' => trans('messages.field_required'),
            'expiry_date.date' => trans('messages.invalid_date'),
            'expiry_date.after' => trans('messages.invalid_date'),
            'afile.mimes' => trans('messages.valid_ad_image_type'),
            'afile.max' => trans('messages.ad_file_max_size'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$req->filled('advertisingsid')) {
                $ca = new Advertising();
            }
            if ($req->hasFile('afile')) {
                if ($req->filled('advertisingsid') && file_exists('storage/app/public/admin/assets/images/advertisings/' . $ca->file)) {
                    unlink('storage/app/public/admin/assets/images/advertisings/' . $ca->file);
                }
                $afile = 'advertisings-' . uniqid() . '.' . $req->afile->getClientOriginalExtension();
                $req->afile->move(storage_path('app/public/admin/assets/images/advertisings/'), $afile);
                $ca->file = $afile;
            }

            $ca->title = $req->title;
            $ca->url = $req->url;
            $ca->expiry_date = $req->expiry_date;
            $ca->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function destroy(string $id)
    {
        try {
            $ca = Advertising::find($id);
            if (!empty($ca)) {
                if (file_exists('storage/app/public/admin/assets/images/advertisings/' . $ca->file)) {
                    unlink('storage/app/public/admin/assets/images/advertisings/' . $ca->file);
                }
                $ca->delete();
                return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
            }
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function status(Request $request)
    {
        $cu = Advertising::where('id', $request->id)->where('is_available', $request->status == 2 ? 1 : 2)->first();
        if (empty($cu)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        try {
            $cu->is_available = $request->status;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
