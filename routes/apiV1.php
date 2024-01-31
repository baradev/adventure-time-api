<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EpisodeController;
use App\Http\Controllers\KingdomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/kingdoms', [KingdomController::class, 'index'])->name('kingdoms.index');
Route::get('/kingdoms/paginated', [KingdomController::class, 'paginated'])->name('kingdoms.paginated');
Route::get('/kingdoms/slug/{slug}', [KingdomController::class, 'showBySlug'])->name('kingdoms.showBySlug');
Route::get('/kingdoms/{ids}', [KingdomController::class, 'show'])->name('kingdoms.show');


Route::get('/episodes', [EpisodeController::class, 'index'])->name('episodes.index');
Route::get('/episodes/paginated', [EpisodeController::class, 'paginated'])->name('episodes.paginated');
Route::get('/episodes/slug/{slug}', [EpisodeController::class, 'showBySlug'])->name('episodes.showBySlug');
Route::get('/episodes/{ids}', [EpisodeController::class, 'show'])->name('episodes.show');

Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/characters/paginated', [CharacterController::class, 'paginated'])->name('characters.paginated');
Route::get('/characters/slug/{slug}', [CharacterController::class, 'showBySlug'])->name('characters.showBySlug');
Route::get('/characters/{ids}', [CharacterController::class, 'show'])->name('characters.show');

Route::get('/status', function () {
    return response()->json([
        'status' => 'ok'
    ]);
})->name('status');

Route::fallback(function (){
    return response()->json([
        'message' => 'Endpoint not found'
    ]);
});