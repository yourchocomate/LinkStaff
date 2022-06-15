<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class ProfileController extends Controller
{
    public function profile() {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function followPerson($id) {
        $user = Auth::user();
        $followed = json_decode($user->followed_persons);
        $person = User::findOrFail($id);

        if(!$person) {
            $response = [
                'success' => false,
                'message' => 'Person Not Exists'
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
            $user->followed_persons = json_encode($followed);
            $user->save();
            $person->followers += 1;
            $person->save();
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

    public function unFollowPerson($id) {
        $user = Auth::user();
        $followed = json_decode($user->followed_persons);
        $person = User::findOrFail($id);

        if(!$person) {
            $response = [
                'success' => false,
                'message' => 'Person Not Exists'
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
        $user->followed_persons = json_encode($data);
        $person->followers -= 1;
        
        if($user->save() && $person->save()) {
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
