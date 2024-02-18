<?php

use App\Http\Controllers\ApiAuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('api')->post('/login', [ApiAuthController::class, 'login'])->name('apiLogin');
Route::post('/register', [ApiAuthController::class, 'store'])->name('apiRegister');
Route::middleware(['auth:sanctum'])->post('/logout', [ApiAuthController::class, 'logout'])->name('apiLogout');

require __DIR__.'/user.php';
//require __DIR__.'/properties.php';
require __DIR__.'/properties.php';