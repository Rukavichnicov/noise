<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//TODO Изменить на свою начальную страницу
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

$groupData = [
    'namespace' => 'App\Http\Controllers\Noise\Main',
    'prefix' => 'noise/main',
];

Route::group($groupData, function () {
    //Для всех пользователей
    $methods = ['index'];
    Route::resource('sources', \App\Http\Controllers\Noise\Main\NoiseSourceController::class)
        ->only($methods)
        ->names('noise.main.sources');
});
