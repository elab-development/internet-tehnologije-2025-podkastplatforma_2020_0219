<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\KategorijaController;
use App\Http\Controllers\UserController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/podcasti',[PodcastController::class,'index']);
      Route::get('kategorije',[KategorijaController::class,'index']);
      Route::get('users/autori',[UserController::class,'vratiAutore']);
      Route::get('/users/podcasti', [UserController::class, 'mojiPodcasti']);
      Route::get('users/favorites/podcasti',[UserController::class,'getFavorites']);


});