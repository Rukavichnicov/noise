<?php

use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
$groupDataMain = [
    'namespace' => 'App\Http\Controllers\Api\Main',
];
Route::group($groupDataMain, function () {
    Route::resource('ajax', \App\Http\Controllers\Api\Main\AjaxController::class)
        ->only(['index'])
        ->names('api.ajax');;
});
