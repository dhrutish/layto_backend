<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlansController extends Controller
{
    public function index()
    {
        return view('admin.plans.index');
    }
    public function list(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 10;
        $order = $request->filled('order') ? $request->order : 'DESC';
        $sort = $request->filled('sort') ? $request->sort : 'id';
        $sql = Plans::orderBy($sort, $order);
        if ($request->filled('search')) {
            $sql = $sql->where('from_coins', 'LIKE', "%$request->search%")->orWhere('to_coins', 'LIKE', "%$request->search%")->orWhere('additional_coins_pr', 'LIKE', "%$request->search%");
        }
        $total = $sql->count();
        if ($request->filled('limit')) {
            $sql =  $sql->skip($offset)->take($limit);
        }
        $res = $sql->get();
        $bulkData = [];

        $bulkData['rows'] = [];
        $bulkData['total'] = $total;
        $cnt = 1;
        foreach ($res as $row) {
            $action = '<i class="fa-regular fa-ellipsis cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false"></i><ul class="dropdown-menu"><li><a class="dropdown-item edit-details" data-bs-toggle="modal" data-bs-target="#planmodal" data-pid="' . $row->id . '" data-from-coins="' . $row->from_coins . '" data-to-coins="' . $row->to_coins . '" data-additional-coins-pr="' . $row->additional_coins_pr . '" href="javascript:;">' . trans('labels.edit') . '</a></li></ul>';
            $bulkData['rows'][] = [
                'id' => $cnt++,
                'from_coins' => $row->from_coins,
                'to_coins' => $row->to_coins == 0 ? trans('labels.up_to_n') : $row->to_coins,
                'additional_coins_pr' => $row->additional_coins_pr.'%',
                'action' => $action,
            ];
        }
        return response()->json($bulkData);
    }

    public function store(Request $request)
    {
        if ($request->filled('planid')) {
            $cu = Plans::where('id', $request->planid)->first();
            if (empty($cu)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_plan')], 200);
            }
        }
        $validator = Validator::make($request->all(), [
            'from_coins' => 'bail|required|numeric|min:1',
            'to_coins' => 'bail|required_if:upton,2|numeric|min:1|gt:from_coins',
            'additional_coins_pr' => 'bail|nullable|sometimes|numeric|between:0,100',
        ], [
            '*.required' => trans('messages.field_required'),
            'from_coins.numeric' => trans('messages.numeric_only'),
            'to_coins.numeric' => trans('messages.numeric_only'),
            'to_coins.gt' => trans('messages.gt_from_coins'),
            'additional_coins_pr.numeric' => trans('messages.numeric_only'),
            'additional_coins_pr.between' => trans('messages.coins_pr_between'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        try {
            if (!$request->filled('planid')) {
                $cu = new Plans();
            }
            $cu->from_coins = $request->from_coins;
            $cu->to_coins = $request->upton == 1 ? 0 : $request->to_coins;
            $cu->additional_coins_pr = $request->additional_coins_pr ?? 0;
            $cu->save();
            return response()->json(['status' => 1, 'message' => trans('messages.success')], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
