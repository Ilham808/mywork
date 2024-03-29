<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [\App\Http\Controllers\UserController::class, 'register']);
Route::post('login', [\App\Http\Controllers\UserController::class, 'login']);

Route::middleware(\App\Http\Middleware\ApiAuthMiddleware::class)->group(function () {
    Route::get('users/profile', [\App\Http\Controllers\UserController::class, 'profile']);
    Route::delete('logout', [\App\Http\Controllers\UserController::class, 'logout']);

    Route::get('projects', [\App\Http\Controllers\ProjectController::class, 'search']);
    Route::post('projects', [\App\Http\Controllers\ProjectController::class, 'store']);
    Route::get('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'show']);
    Route::put('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'update']);
    Route::delete('projects/{id}', [\App\Http\Controllers\ProjectController::class, 'destroy']);

    Route::post('projects/{idProject}/tasks', [\App\Http\Controllers\TaskController::class, 'create']);
    Route::get('projects/{idProject}/tasks', [\App\Http\Controllers\TaskController::class, 'search']);
    Route::put('projects/{idProject}/tasks/{idTask}', [\App\Http\Controllers\TaskController::class, 'update']);
    Route::get('projects/{idProject}/tasks/{idTask}', [\App\Http\Controllers\TaskController::class, 'show']);
    Route::put('projects/{idProject}/tasks/{idTask}/status', [\App\Http\Controllers\TaskController::class, 'updateStatus']);
    Route::delete('projects/{idProject}/tasks/{idTask}', [\App\Http\Controllers\TaskController::class, 'destroy']);

});