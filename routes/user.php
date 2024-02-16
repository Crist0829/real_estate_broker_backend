<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\UserController;
use App\Models\Property;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum'])->group(function (){

    Route::put('/user/update/{id}', [UserController::class, 'update']);
    Route::put('/user/updatePassword/{id}', [UserController::class, 'updatePassword']);
});
