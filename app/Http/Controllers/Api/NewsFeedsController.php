<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsFeed;
use Illuminate\Http\Request;

class NewsFeedsController extends Controller
{
    public function news_feed_list(Request $request) {
        try {
            $news_feed_list = NewsFeed::where('is_featured',2)->get()->makeVisible('updated_at')->makeHidden(['description','is_featured','image']);
            return response()->json(['status' => 1, 'message' => 'Success', 'news_feed_list' => $news_feed_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function top_news_feed_list(Request $request) {
        try {
            $top_news_feed_list = NewsFeed::with('industry_type')->where('is_featured',1)->get()->makeVisible('updated_at')->makeHidden(['description','is_featured','image']);
            return response()->json(['status' => 1, 'message' => 'Success', 'top_news_feed_list' => $top_news_feed_list], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
    public function news_details(Request $request) {
        try {
            $news_details = NewsFeed::where('id', $request->id)->first();
            if ($news_details) {
                $news_details->makeVisible('updated_at')->makeHidden(['is_featured', 'image']);
            } else {
                return response()->json(['status' => 0, 'message' => "News Feed Not Found"], 200);
            }
            return response()->json(['status' => 1, 'message' => 'Success', 'news_details' => $news_details], 200);
        } catch (\Throwable $th) {
            return errorResponse($th->getMessage());
        }
    }
}
