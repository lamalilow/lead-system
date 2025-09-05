<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SourceController;



Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});


Route::prefix('leads')->group(function () {
    Route::post('/', [LeadController::class, 'store']); // публично
});


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('leads', LeadController::class)->except(['store']);

    Route::get('leads/{id}/comments', [CommentController::class, 'index']);
    Route::post('leads/{id}/comments', [CommentController::class, 'store']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);

    Route::apiResource('sources', SourceController::class);
});
