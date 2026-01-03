<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function () {
    return "test route";
});

Route::get('/categories',[
    CategoryController::class,
    'index'
]);

Route::post('/categories',[
    CategoryController::class,
    'store'
]);



