<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PagePost;
use Illuminate\Http\Request;
use Auth;

class PageController extends Controller
{
    // Create Page With PageName
    public function createPage(Request $request) {
        
        // Insert new page on pages table with creatorId and pageName
        $page = Page::create([
            'user_id' => Auth::user()->id,
            'name' => $request->page_name
        ]);

        // Return success if page inserts
        if($page) {
            $response = [
                'success' => true,
                'message' => 'Page Created Successfully'
            ];
            return response()->json($response, 200);
        }

        // return if not inserts
        $response = [
            'success' => false,
            'message' => 'Page Not Created'
        ];
        return response()->json($response, 200);
    }

    //  Follow a page by pageId
    public function followPage($id) {

        // Get the loggedIn user
        $user = Auth::user();
        // Get the array of followedPageId from user
        $followed = json_decode($user->followed_pages);
        // Check if Page Exists by pageId
        $page = Page::findOrFail($id);

        // Return if page not exists
        if(!$page) {
            $response = [
                'success' => false,
                'message' => 'Page Not Exists'
            ];
            return response()->json($response, 404);
        }

        // Return already followed if the pageId is found in the followedPages array
        if(in_array($id, $followed)) {
            $response = [
                'success' => false,
                'message' => 'Already Followed'
            ];
            return response()->json($response, 200);
        }

        // Push the pageId to existing array of followedPageId's
        if(array_push($followed,intval($id))) {
            // Assign the new array with latest followed pageId to followedPages
            $user->followed_pages = json_encode($followed);
            $user->save();
            // Increment the followers count
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

        // Get the loggedIn user
        $user = Auth::user();
        // Get the array of followedPageId from user
        $followed = json_decode($user->followed_pages);
        // Check if Page Exists by pageId
        $page = Page::findOrFail($id);

        // Return if page not exists
        if(!$page) {
            $response = [
                'success' => false,
                'message' => 'Page Not Exists'
            ];
            return response()->json($response, 404);
        }

        // Return already unfollowed if the pageId is not found in the followedPages array
        if(!in_array($id, $followed)) {
            $response = [
                'success' => false,
                'message' => 'Already Unfollowed'
            ];
            return response()->json($response, 200);
        }
        
        // Returns the array except the request pageId
        $data = array_filter($followed, fn ($e) => $e != intval($id));
        $user->followed_pages = json_encode($data);
        // Decrement the followers by 1
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
