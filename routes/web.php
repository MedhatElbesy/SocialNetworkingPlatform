<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LikeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });




Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('users/{id}', [UserController::class, 'show'])->name('profiles.show');

Route::get('/', [FeedController::class, 'index'])->name('feeds.index');

Route::resource('posts', PostController::class)->only(['create', 'store', 'show']);

Route::post('posts/{postId}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::post('posts/{postId}/likes', [LikeController::class, 'toggle'])->name('likes.toggle');
