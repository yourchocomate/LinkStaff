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

    //  Follow a person by personId
    public function followPerson($id) {

        // Get the loggedIn user
        $user = Auth::user();
        // Get the array of followedPersonId from user
        $followed = json_decode($user->followed_persons);
        // Check if Person Exists by personId
        $person = User::find($id);

        // Return if person not exists
        if(!$person) {
            $response = [
                'success' => false,
                'message' => 'Person Not Exists'
            ];
            return response()->json($response, 404);
        }

        // Return already followed if the personId is found in the followedPersons array
        if(in_array($id, $followed)) {
            $response = [
                'success' => false,
                'message' => 'Already Followed'
            ];
            return response()->json($response, 200);
        }

        // Push thepersonId to existing array of followedPersonId's
        if(array_push($followed,intval($id))) {
            // Assign the new array with latest followed personId to followedpersons
            $user->followed_persons = json_encode($followed);
            $user->save();
            // Increment the followers count
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

    //  Unfollow a person by personId
    public function unFollowPerson($id) {

        // Get the loggedIn user
        $user = Auth::user();
        // Get the array of followedPersonId from user
        $followed = json_decode($user->followed_persons);
        // Check if Person Exists by personId
        $person = User::find($id);

        // Return if person not exists
        if(!$person) {
            $response = [
                'success' => false,
                'message' => 'Person Not Exists'
            ];
            return response()->json($response, 404);
        }

        // Return already unfollowed if the personId is not found in the followedPersons array
        if(!in_array($id, $followed)) {
            $response = [
                'success' => false,
                'message' => 'Already Unfollowed'
            ];
            return response()->json($response, 200);
        }
        
        // Returns the array except the request personId
        $data = array_filter($followed, fn ($e) => $e != intval($id));
        $user->followed_persons = json_encode($data);
        // Decrement the followers by 1
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
