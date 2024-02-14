<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function (){

    Route::get('/properties', [PropertyController::class, 'index']);


});

