<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // Pastikan namespace sesuai
use App\Http\Controllers\Api\TasksController; // Pastikan namespace sesuai
use App\Http\Controllers\Api\CategoriesController; // Pastikan namespace sesuai
use App\Http\Controllers\Api\TaskCategoriesController; // Pastikan namespace sesuai

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


// Yang belum login
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Yang sudah login
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/change-password', [AuthController::class, 'change_password']);
    Route::post('/search-user', [AuthController::class, 'search']);
    
    Route::resource('tasks', TasksController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('task_categories', TaskCategoriesController::class);
});


