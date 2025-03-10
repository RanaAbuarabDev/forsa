<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('posts',[PostController::class,'index']);
Route::get('post/{id}',[PostController::class,'show']);
Route::post('CreatePost',[PostController::class,'store']);
Route::put('UpdatePost/{id}',[PostController::class,'update']);
Route::delete('DeletePost/{id}',[PostController::class,'delete']);