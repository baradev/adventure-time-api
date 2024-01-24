<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\KingdomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kingdoms', [KingdomController::class, 'index']);
Route::get('/kingdoms/paginated', [KingdomController::class, 'paginated']);
Route::get('/kingdoms/slug/{slug}', [KingdomController::class, 'showBySlug']);
Route::get('/kingdoms/{id}', [KingdomController::class, 'show']);


Route::get('/episodes', [EpisodeController::class, 'index']);
Route::get('/episodes/paginated', [EpisodeController::class, 'paginated']);
Route::get('/episodes/slug/{slug}', [EpisodeController::class, 'showBySlug']);
Route::get('/episodes/{id}', [EpisodeController::class, 'show']);

Route::get('/characters', [CharacterController::class, 'index']);
Route::get('/characters/paginated', [CharacterController::class, 'paginated']);
Route::get('/characters/slug/{slug}', [CharacterController::class, 'showBySlug']);
Route::get('/characters/{id}', [CharacterController::class, 'show']);

Route::fallback(function (){
    return response()->json([
        'message' => 'Endpoint not found'
    ]);
});