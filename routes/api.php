<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('games', [GameController::class, 'index']);
Route::post('games', [GameController::class, 'store']);
Route::get('games/{id}', [GameController::class, 'show']);
Route::put('games/{id}', [GameController::class, 'update']);
Route::delete('games/{id}', [GameController::class, 'destroy']);
Route::get('games/genre/{genre}', [GameController::class, 'getByGenre']);
