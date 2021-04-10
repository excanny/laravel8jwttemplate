<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataController;

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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::get('open', [DataController::class, 'open']);
// Route::get('addexperience', [DataController::class, 'addexperience']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('closed', [DataController::class, 'closed']);
    Route::post('addexperience', [DataController::class, 'addexperience']);
    Route::get('experiences', [DataController::class, 'experiences']);
    Route::post('addeducation', [DataController::class, 'addeducation']);
    Route::get('educations', [DataController::class, 'educations']);
    Route::post('addportfolio', [DataController::class, 'addportfolio']);
});