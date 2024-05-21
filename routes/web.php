<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('test', function () {
//     return 'welcome';
// });

// Route::middleware('api')->group(function () {
//     Route::get('/items', [AuthController::class, 'index']);
// });
