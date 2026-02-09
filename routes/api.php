<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\UserController;
use App\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return "test route";
});
Route::get('/test/{x}', function ($x1) {
    return "test route $x1";
});

// Route::get('/categories', [CategoryController::class, 'index']);
// Route::post('/categories', [CategoryController::class, 'store']);
// Route::put('/categories/{identifier}', [CategoryController::class, 'update']);
// Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {

    // rout group
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categories', 'index');
        Route::post('/categories', 'store')->middleware("user-type:admin");
        Route::put('/categories/{identifier}', 'update');
        Route::delete('/categories/{id}', 'destroy');
    });

    // Route::get('/books',[
    //     CategoryController::class,
    //     'index'
    // ]);

    // Route::resource('books', BookController::class);
    Route::apiResource('books', BookController::class);
    // Route::apiResource('books', BookController::class)->except('show');
    // Route::apiResource('books', BookController::class)->only('show', 'destroy');

    Route::apiResource('authors', AuthorController::class);
});

Route::get("env", function () {
    return env('APP_NAME', 'not found');
});

Route::get("config", function () {
    return config('app.name', 'not found');
});
// storage لشوف مسار الـ 
Route::get('public-path', function () {
    return storage_path('app/public');
});

// ******************
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);

// Route::controller(AuthController::class)->group(function () {
//     Route::post('register', 'register');
//     Route::post('login', 'login');
//     Route::post('logout', 'logout');
// });

// Get All Users
Route::get('/users', [ApiUserController::class, 'index'])
    ->middleware('auth:sanctum');
