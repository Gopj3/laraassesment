<?php

use App\Http\Controllers\Users\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('{any}', function () {
//    return view('home');
//})->where('any', '.*');

Route::softDeletes('users');
Route::middleware('auth')->resource('users', UsersController::class, ['except' => ['update']]);
Route::middleware('auth')->post('users/{user}', [UsersController::class, 'update'])->name('users.update');
