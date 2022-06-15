<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PagePost;
use Illuminate\Http\Request;
use Auth;

class PageController extends Controller
{
    public function createPage(Request $request) {
        $page = Page::create([
            'user_id' => Auth::user()->id,
            'name' => $request->page_name
        ]);

        if($page) {
            $response = [
                'success' => true,
                'message' => 'Page Created Successfully'
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Page Not Created'
        ];
        return response()->json($response, 200);
    }

    public function followPage($id) {
        $user = Auth::user();
        $followed = json_decode($user->followed_pages);
        
        $page = Page::findOrFail($id);

        if(!$page) {
            $response = [
                'success' => false,
                'message' => 'Page Not Exists'
            ];
            return response()->json($response, 404);
        }

        if(in_array($id, $followed)) {
            $response = [
                'success' => false,
                'message' => 'Already Followed'
            ];
            return response()->json($response, 200);
        }

        if(array_push($followed,intval($id))) {
            $user->followed_pages = json_encode($followed);
            $user->save();
            $page->followers += 1;
            $page->save();
            $response = [
                'success' => true,
                'message' => 'Followed'
            ];
            return response()->json($response, 200);
        }
        $response = [
            'success' => false,
            'message' => "Can't follow"
        ];
        return response()->json($response, 200);
        
    }

    public function unFollowPage($id) {
        $user = Auth::user();
        $followed = json_decode($user->followed_pages);
        $page = Page::findOrFail($id);

        if(!$page) {
            $response = [
                'success' => false,
                'message' => 'Page Not Exists'
            ];
            return response()->json($response, 404);
        }

        if(!in_array($id, $followed)) {
            $response = [
                'success' => false,
                'message' => 'Already Unfollowed'
            ];
            return response()->json($response, 200);
        }
    
        $data = array_filter($followed, fn ($e) => $e != intval($id));
        $user->followed_pages = json_encode($data);
        $page->followers -= 1;
            
        if($user->save() && $page->save()) {
            $response = [
                'success' => true,
                'message' => 'Unfollowed'
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => "Can't unfollow"
        ];
        return response()->json($response, 200);
        
    }
}
