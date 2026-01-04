<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return "test route";
});

Route::get('/test/{x}', function ($x1) {
    return "test route $x1";
});

Route::get('/categories',[
    CategoryController::class,
    'index'
]);

Route::post('/categories',[
    CategoryController::class,
    'store'
]);

Route::put('/categories/{identifier}',[
    CategoryController::class,
    'update'
]);

Route::delete('/categories/{id}',[
    CategoryController::class,
    'destroy'
]);



// Route::get('/books',[
//     CategoryController::class,
//     'index'
// ]);

// Route::resource('books', BookController::class);
Route::apiResource('books', BookController::class);
// Route::apiResource('books', BookController::class)->except('show');
// Route::apiResource('books', BookController::class)->only('show', 'destroy');

Route::apiResource('authors', AuthorController::class);