<?php

use Illuminate\Support\Facades\Route;

// Prevent web catch-all from stealing API endpoints
Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api).*$');