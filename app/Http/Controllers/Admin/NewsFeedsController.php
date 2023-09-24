<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IndustryTypes;
use App\Models\NewsFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsFeedsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $sql = NewsFeed::orderBy($sort, $order);
            if ($request->filled('search')) {
                $sql = $sql->where('url', 'LIKE', "%$request->search%")->orWhere('expiry_date', 'LIKE', "%$request->search%");
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
                $top_feed = '<input class="form-check-input" type="checkbox" onclick="changestatuss(' . $row->id . ',' . ($row->is_featured == 1 ? '2' : '1') . ',' . chr(39) . route('news-feeds.status') . chr(39) . ',this)" ' . ($row->is_featured == 1 ? 'checked' : '') . ' />';

                $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#newsfeedsmodal" data-nfid="' . $row->id . '" data-itid="'.$row->industry_types_id.'" data-title="' . $row->title . '" data-description="' . $row->description . '" data-file-url="' . $row->image_url . '" href="javascript:;">' . trans('labels.edit') . '</a></li><li><a class="dropdown-item text-danger" href="javascript:;" onclick="deletedata(' . chr(39) . route('news-feeds.destroy', [$row->id]) . chr(39) . ')">' . trans('labels.delete') . '</a></li></ul>';

                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'image' => '<img src="' . $row->image_url . '" alt="news-feeds image"  class="rounded" height="50" width="50">',
                    'industry' => $row->industry_type->title_en,
                    'title' => $row->title,
                    'description' => Str::limit(strip_tags($row->description), 50),
                    'top_feed' => '<div class="form-check form-switch">' . $top_feed . '</div>',
                    'created_at' => date_time_formated($row->created_at),
                    'action' => $action,
                ];
            }
            return response()->json($bulkData);
        }
        $itypes = IndustryTypes::orderByDesc('id')->get();
        return view('admin.newsfeeds.index', compact('itypes'));
    }
    public function store(Request $request)
    {
        if ($request->filled('newsfeedsid')) {
            $ca = NewsFeed::where('id', $request->newsfeedsid)->first();
            if (empty($ca)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_newsfeeds')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'title' => 'bail|required',
            'description' => 'bail|required',
            'industry' => 'bail|required|exists:industry_types,id',
            'image' => $request->filled('newsfeedsid') ? 'bail|image|mimes:jpg,jpeg,png,gif,svg' : 'bail|required|image|mimes:jpg,jpeg,png,gif,svg',
        ], [
            '*.required' => trans('messages.field_required'),
            'image.image' => trans('messages.valid_image'),
            'image.mimes' => trans('messages.valid_image_type'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('newsfeedsid')) {
                $ca = new NewsFeed();
            }
            if ($request->hasFile('image')) {
                if ($request->filled('newsfeedsid') && file_exists('storage/app/public/admin/assets/images/advertisings/' . $ca->image)) {
                    unlink('storage/app/public/admin/assets/images/advertisings/' . $ca->image);
                }
                $image = 'news-' . uniqid() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(storage_path('app/public/admin/assets/images/advertisings/'), $image);
                $ca->image = $image;
            }

            $ca->industry_types_id = $request->industry;
            $ca->title = $request->title;
            $ca->description = $request->description;
            $ca->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function destroy(string $id)
    {
        try {
            $ca = NewsFeed::find($id);
            if (!empty($ca)) {
                if (file_exists('storage/app/public/admin/assets/images/advertisings/' . $ca->image)) {
                    unlink('storage/app/public/admin/assets/images/advertisings/' . $ca->image);
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
        $cnf = NewsFeed::where('id', $request->id)->where('is_featured', $request->status == 2 ? 1 : 2)->first();
        if (empty($cnf)) {
            return response()->json(['status' => 0, 'message' => trans('messages.invalid_request')], 200);
        }
        try {
            $cnf->is_featured = $request->status;
            $cnf->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblnname' => 'table_' . str_replace('-', '', basename(url()->previous()))], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
