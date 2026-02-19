<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\KategorijaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmisijaController;
use App\Http\Controllers\StatistikaController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/podcasti',[PodcastController::class,'index']);
      Route::get('kategorije',[KategorijaController::class,'index']);
      Route::get('users/autori',[UserController::class,'vratiAutore']);
      Route::get('/users/podcasti', [UserController::class, 'mojiPodcasti']);
      Route::get('users/favorites/podcasti',[UserController::class,'getFavorites']);
      Route::post('users/favorites/{id}',[UserController::class,'addToFavorites']);
      Route::delete('users/favorites/{id}',[UserController::class,'removeFavorite']);
      Route::get('users/autori/favorites',[UserController::class,'getUsersOfFavoritesPodcasts']);
      Route::get('podcasti/{id}',[PodcastController::class,'show']);
      Route::delete('/podcasti/{id}',[PodcastController::class,'destroy']);
      Route::get('emisije/{id}',[EmisijaController::class,'show']);
      Route::get('/emisije/file/{id}', [EmisijaController::class, 'vratiFile'])->name('emisija.file');
      Route::post('podcasti',[PodcastController::class,'store']);
      Route::put('podcasti/{id}',[PodcastController::class,'update']);
      Route::post('kategorije',[KategorijaController::class,'store']);
      Route::get('statistika',[StatistikaController::class,'konacnaStatistika']);
     Route::get('/users', [UserController::class, 'index']);
      Route::delete('users/{id}',[UserController::class,'destroy']);
     

});