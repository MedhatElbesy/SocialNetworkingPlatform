<?php


// use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UserController::class)->group(function(){
        Route::get('users','index');
        Route::get('user/timeline','getTimeline');
        Route::get('user/{id}','show');
        Route::put('user/update/{id}','update');
    });

    Route::controller(FriendshipController::class)->group(function(){
        Route::get('friends','listFriends');
        Route::get('friends/pending','listPendingRequests');
        Route::post('friend-request/send/{friendId}','sendRequest');
        Route::post('friend-request/accept/{userId}','acceptRequest');
        Route::post('friend-request/reject/{userId}','rejectRequest');
    });

    Route::controller(CommentController::class)->group(function (){
        Route::get('posts/{postId}/comments','index');
        Route::post('comments','store');
        Route::put('comments/{id}','update');
        Route::delete('comments/{id}','destroy');
    });


    Route::apiResource('post', PostController::class);
    Route::get('posts/{postId}/likes', [PostController::class, 'showPostLikes']);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('posts/{postId}/likes', [LikeController::class, 'toggle']);
});
