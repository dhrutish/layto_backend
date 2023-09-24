<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offset = $request->filled('offset') ? $request->offset : 0;
            $limit = $request->filled('limit') ? $request->limit : 10;
            $order = $request->filled('order') ? $request->order : 'DESC';
            $sort = $request->filled('sort') ? $request->sort : 'id';
            $sql = Transactions::orderBy($sort, $order);
            if ($request->filled('search')) {
                $sql = $sql->where('final_coins', 'LIKE', "%$request->search%")->orWhere('transaction_id', 'LIKE', "%$request->search%");
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
                $days_left = '-';
                if (in_array($row->type, [1, 2, 3, 6, 9, 11]) && !in_array($row->is_coins_used,[1,4])) {
                    $days_left = Carbon::parse($row->created_at)->addDays($row->coin_expire_days > 0 ? $row->coin_expire_days : 365)->diffInDays();
                }
                $bulkData['rows'][] = [
                    'id' => $cnt++,
                    'image' => '<img src="' . image_path(in_array($row->type, [1, 2, 3, 6, 9, 11]) ? 'in.png' : 'out.png') . '" alt="status image"  class="rounded" height="30" width="30">',
                    'transaction_id' => $row->transaction_id,
                    'final_coins' => $row->final_coins,
                    'amount' => $row->amount > 0 ? currency_formated($row->amount) : '-',
                    'description' => $row->description,
                    'created_at' => date_time_formated($row->created_at),
                    'days_left' => $days_left,
                ];
            }
            return response()->json($bulkData);
        }
        return view('admin.transactions.index');
    }
    public function manage_coins(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|exists:users,id',
            'type' => 'bail|required|in:add,deduct',
            'coins' => 'bail|required|min:1',
            'description' => 'bail|required',
        ], [
            '*.required' => trans('messages.field_required'),
            'id.exists' => trans('messages.invalid_user'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => trans('messages.error'), 'errors' => $validator->getMessageBag()], 200);
        }
        DB::beginTransaction();
        try {
            $cu = User::where('id', $request->id)->whereIn('type', [3, 4])->available()->first();
            if (empty($cu)) {
                return response()->json(['status' => 0, 'message' => trans('messages.invalid_user')], 200);
            }
            $avail_coins = userCoins($cu->id);
            if ($request->type != 'add') {
                if ($request->coins > $avail_coins) {
                    return response()->json(['status' => 0, 'message' => trans('messages.insufficient_coins') . '<br> Available coins are : ' . $avail_coins . ' <i class="fa-light fa-coins text-warning"></i>'], 200);
                }
                $deductResult = deductCoins($cu->id, $request->coins);
                if (!$deductResult) {
                    throw new \Exception('Unable to deduct the coins!');
                }
            }
            $ct = new Transactions();
            $ct->user_id = $cu->id;
            $ct->final_coins = $request->coins;
            $ct->description = $request->description;
            $ct->type = $request->type == 'add' ? 3 : 4;
            $ct->coin_expire_days = $request->type == 'add' ? settingsdata()->coin_expire_days : 0;
            $ct->save();
            DB::commit();
            return response()->json(['status' => 1, 'message' => trans('messages.success'), 'tblname' => 'table_transactions'], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return errorResponse($th->getMessage());
        }
    }
}
