<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BugController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Bug API routes
Route::prefix('bugs')->group(function () {
    Route::get('/', [BugController::class, 'index']);
    Route::post('/', [BugController::class, 'store']);
    Route::get('/stats', [BugController::class, 'stats']);
    Route::get('/{bug}', [BugController::class, 'show']);
    Route::put('/{bug}', [BugController::class, 'update']);
    Route::delete('/{bug}', [BugController::class, 'destroy']);
});
