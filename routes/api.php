<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;

// Menu Route
Route::get('/menu', [MenuController::class, 'index']);

// Order Routes
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);