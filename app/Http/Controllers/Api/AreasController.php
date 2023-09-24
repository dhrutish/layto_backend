<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Areas;
use App\Models\Cities;
use App\Models\States;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "state_id" => 'required|exists:states,id',
            "city_id" => 'required|exists:cities,id',
            "title" => 'required',
        ]);
        try {
            $checkstate = States::where('id', $request->state_id)->available()->first();
            if (empty($checkstate)) {
                return response()->json(['status' => 0, 'message' => 'Invalid State'], 200);
            }
            $checkcity = Cities::where('id', $request->city_id)->where('state_id', $request->state_id)->available()->first();
            if (empty($checkcity)) {
                return response()->json(['status' => 0, 'message' => 'Invalid City'], 200);
            }
            $area = new Areas();
            $area->state_id = $request->state_id;
            $area->city_id = $request->city_id;
            $area->title_en = $request->title;
            $area->title_hi = $request->title;
            $area->title_gj = $request->title;
            $area->save();
            return response()->json(['status' => 1, 'message' => 'Success', 'area_id' => $area->id], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
