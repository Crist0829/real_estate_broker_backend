<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (){

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::get('/all-properties', [PropertyController::class, 'indexAll']);
    Route::get('/properties/store', [PropertyController::class, 'store']);
});

