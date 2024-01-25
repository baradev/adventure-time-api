<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route('info'));
})->name('home');

Route::get('/info', function () {
    return response()->json([
        'app' => env('APP_NAME'),
        'version' => env('APP_VERSION'),
        'author' => env('APP_AUTHOR'),
        'github' => env('APP_GITHUB_URL'),
        'documentation' => env('APP_DOCUMENTATION_URL'),
        'endpoints' => [
            'kingdoms' => [
                'list' => route('kingdoms.index'),
                'paginated' => route('kingdoms.paginated'),
                'show' => route('kingdoms.show', ['id' => 1]),
                'showBySlug' => route('kingdoms.showBySlug', ['slug' => 'kingdom-slug']),
            ],
            'episodes' => [
                'list' => route('episodes.index'),
                'paginated' => route('episodes.paginated'),
                'show' => route('episodes.show', ['id' => 1]),
                'showBySlug' => route('episodes.showBySlug', ['slug' => 'episode-slug']),
            ],
            'characters' => [
                'list' => route('characters.index'),
                'paginated' => route('characters.paginated'),
                'show' => route('characters.show', ['id' => 1]),
                'showBySlug' => route('characters.showBySlug', ['slug' => 'character-slug']),
            ],
        ]
    ]);
})->name('info');

Route::fallback(function (){
    return response()->json([
        'message' => 'Endpoint not found',
        'documentation' => env('APP_DOCUMENTATION_URL'),
        'base_url' => env('APP_URL'),
        'url_requested' => url()->current(),
    ]);
});