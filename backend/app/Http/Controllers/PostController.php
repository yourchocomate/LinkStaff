<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;
use Auth;
use DB;

class PostController extends Controller
{
    /**
     *  Get feeds for user by followed users and pages
     */
    public function feeds() {

        $user = Auth::user();
        $pages = json_decode($user->followed_pages);
        $persons = json_decode($user->followed_persons);

        $posts = DB::table('posts')
                    ->whereIn('user_id', $persons)
                    ->orWhereIn('page_id', $pages)
                    ->get();
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
        /**
         *  @post_content contains object with key "title" and "body"
         */
        $post = Post::create([
            'page_id' => $pageId,
            'post_content' => json_encode($request->post_content)
        ]);

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
