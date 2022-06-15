<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 *  Auth Routes
 */
    Route::prefix('auth')->group(function () {

        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

    });

/**
 *  User Routes
 */
    Route::middleware('auth:sanctum')->group(function () {
        
        // Logged in user profile
        Route::post('profile', [ProfileController::class, 'profile']);
        // follow a person by personId
        Route::post('follow/person/{personId}', [ProfileController::class, 'followPerson']);
        // Unfollow a followed person by personId
        Route::post('unfollow/person/{personId}', [ProfileController::class, 'unFollowPerson']);


        
        // Make a post by person
        Route::post('person/attach-post',[PostController::class, 'makeUserPost']);
        // Get posts from people and pages followed
        Route::post('person/feed', [PostController::class, 'feeds']);



        // Create page by name
        Route::post('page/create', [PageController::class, 'createPage']);
        // Follow a page by pageId
        Route::post('follow/page/{pageId}', [PageController::class, 'followPage']);
        // Unfollow a followed page by pageId
        Route::post('unfollow/page/{pageId}', [PageController::class, 'unFollowPage']);
        // Make a post from page by pageId
        Route::post('page/{pageId}/attach-post',[PostController::class, 'makePagePost']);

        
    });