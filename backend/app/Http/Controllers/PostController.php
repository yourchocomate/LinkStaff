<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use App\Models\Page;
use Illuminate\Http\Request;
use Auth;
use DB;

class PostController extends Controller
{
    /**
     *  Get feeds for user by followed users and pages
     */
    public function feeds() {
        
        // Get LoggedIn User
        $user = Auth::user();
        // Get the array of followed page ids
        $pages = json_decode($user->followed_pages);
        // Get the array of followed person ids
        $persons = json_decode($user->followed_persons);

        // Retrieve the posts with query builder using whereIn to reduce execution time
        $posts = DB::table('posts')
                    ->whereIn('user_id', $persons)
                    ->orWhereIn('page_id', $pages)
                    ->orderByDesc('id')
                    ->get();
                    
        // Return posts using collection resource;
        return new PostCollection($posts);
    }

    /**
     *  Attach post by user with request object @post_content
     */
    public function makeUserPost(Request $request) {
        
        $user = Auth::user();
        /**
         *  @post_content contains object with key "title" and "body"
         */
        $post = Post::create([
            'user_id' => $user->id,
            'post_content' => json_encode($request->post_content)
        ]);

        // Return success if post created
        if($post) {
            $response = [
                'success' => true,
                'message' => 'Post Created'
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Something went wrong'
        ];
        return response()->json($response, 200);
    }

    /**
     *  Attach post by pageId with request object @post_content
     */
    public function makePagePost(Request $request, $pageId) {
        

        // Check if page exists
        $page = Page::find($pageId);
        // Return if not exists
        if(!$page) {
            $response = [
                'success' => false,
                'message' => 'Page Not Exists'
            ];
            return response()->json($response, 200);
        }

        /**
         *  @post_content contains object with key "title" and "body"
         */
        $post = Post::create([
            'page_id' => $pageId,
            'post_content' => json_encode($request->post_content)
        ]);

        // Return success if post created
        if($post) {
            $response = [
                'success' => true,
                'message' => 'Post Created'
            ];
            return response()->json($response, 200);
        }

        $response = [
            'success' => false,
            'message' => 'Something went wrong'
        ];
        return response()->json($response, 200);
    }
}
