<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CustomCrawlerController;

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

Route::post('/fetch',[CustomCrawlerController::class,'fetchContent']);


Route::get('/items/{id?}',[CustomCrawlerController::class,'getData']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
