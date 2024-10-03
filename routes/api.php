<?php


// use App\Http\Controllers\Auth\LoginController;
// use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\FriendshipController;
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


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::apiResource('users', UserController::class);

Route::middleware('auth')->group(function () {
    Route::post('/logout',[LogoutController::class,'logout']);

    Route::apiResource('posts', PostController::class);

    Route::post('friend-request/send/{friendId}', [FriendshipController::class, 'sendRequest']);
    Route::post('friend-request/accept/{userId}', [FriendshipController::class, 'acceptRequest']);
    Route::post('friend-request/reject/{userId}', [FriendshipController::class, 'rejectRequest']);
    Route::get('friends', [FriendshipController::class, 'listFriends']);
    Route::get('friend-requests', [FriendshipController::class, 'listPendingRequests']);

    Route::controller(CommentController::class)->group(function (){

        Route::get('posts/{postId}/comments','index');
        Route::post('comments','store');
        Route::put('comments/{id}','update');
        Route::delete('comments/{id}','destroy');
    });

});
