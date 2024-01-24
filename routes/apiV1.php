<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\KingdomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// index
// create
// store
// show
// edit
// update
// destroy

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kingdoms', [KingdomController::class, 'index']);
Route::get('/episodes', [EpisodeController::class, 'index']);
Route::get('/characters', [CharacterController::class, 'index']);

Route::fallback(function (){
    return response()->json([
        'message' => 'Endpoint not found'
    ]);
});