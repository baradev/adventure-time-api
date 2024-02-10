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

// base route for the API
Route::get('/', function () {
    return response()->json([
        'app' => env('APP_NAME'),
        'version' => env('APP_VERSION'),
        'author' => env('APP_AUTHOR'),
        'github' => env('APP_GITHUB_URL'),
        'documentation' => env('APP_DOCUMENTATION_URL'),
        'data_count' => [
            'kingdoms' => \App\Models\Kingdom::count(),
            'episodes' => \App\Models\Episode::count(),
            'characters' => \App\Models\Character::count(),
        ],
        'endpoints' => [
            'kingdoms' => [
                'list' => route('kingdoms.index'),
                'paginated' => route('kingdoms.paginated'),
                'show' => route('kingdoms.show', ['ids' => 1]),
                'showBySlug' => route('kingdoms.showBySlug', ['slug' => 'kingdom-slug']),
            ],
            'episodes' => [
                'list' => route('episodes.index'),
                'paginated' => route('episodes.paginated'),
                'show' => route('episodes.show', ['ids' => 1]),
                'showBySlug' => route('episodes.showBySlug', ['slug' => 'episode-slug']),
            ],
            'characters' => [
                'list' => route('characters.index'),
                'paginated' => route('characters.paginated'),
                'show' => route('characters.show', ['ids' => 1]),
                'showBySlug' => route('characters.showBySlug', ['slug' => 'character-slug']),
            ],
        ],
        "info_taken_from" =>[
            "https://adventuretime.fandom.com/wiki/Adventure_Time_Wiki",
            "https://freepngimg.com/cartoon/adventure-time"
        ]
    ]);
})->name('info');

Route::fallback(function (){
    return response()->json([
        'message' => 'Endpoint not found'
    ]);
});