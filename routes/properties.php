<?php

use App\Http\Controllers\PropertyController;
use App\Models\Property;
use Illuminate\Support\Facades\Route;


Route::get('/all-properties', [PropertyController::class, 'indexAll']);

Route::middleware(['auth:sanctum'])->group(function (){

    Route::get('/properties', [PropertyController::class, 'index']);
    Route::post('/properties/store', [PropertyController::class, 'store']);
    Route::post('/property/uploadImage/{id}', [PropertyController::class, 'uploadImage']);
    Route::put('/property/{id}', [PropertyController::class, 'update']);
    Route::post('/property/addPrice/{id}', [PropertyController::class, 'addPrice']);
    Route::delete('/property/{id}', [PropertyController::class, 'delete']);
    Route::get('/property/{id}', [PropertyController::class, 'show']);
    Route::post('property/addCalification/{id}', [PropertyController::class, 'addCalification']);
    Route::delete('property/price/{}', [PropertyController::class, 'deletePrice']);
    Route::delete('property/image/{}', [PropertyController::class, 'deleteImage']);

});


