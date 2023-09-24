<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Skills;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "categories_id" => 'required|exists:categories,id',
            "title" => 'required',
        ]);
        try {
            $check_cat = Categories::where('id', $request->categories_id)->available()->first();
            if (empty($check_cat)) {
                return response()->json(['status' => 0, 'message' => 'Invalid City'], 200);
            }
            $skill = new Skills();
            $skill->categories_id = $request->categories_id;
            $skill->title_en = $request->title;
            $skill->title_hi = $request->title;
            $skill->title_gj = $request->title;
            $skill->save();
            return response()->json(['status' => 1, 'message' => 'Success', 'skills_id' => $skill->id], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
