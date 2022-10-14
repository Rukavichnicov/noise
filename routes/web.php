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

\Illuminate\Support\Facades\Auth::routes();

//TODO Изменить на свою начальную страницу
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

$groupDataMain = [
    'namespace' => 'App\Http\Controllers\Noise\Main',
    'prefix' => 'noise/main',
];
Route::group($groupDataMain, function () {
    //Для всех пользователей
    $methodsSources = ['create', 'store'];
    Route::resource('sources', \App\Http\Controllers\Noise\Main\NoiseSourceController::class)
        ->only(['index'])
        ->names('noise.main.sources');
    Route::resource('sources', \App\Http\Controllers\Noise\Main\NoiseSourceController::class)
        ->only($methodsSources)
        ->names('noise.main.sources')
        ->middleware('auth');

    $methodsBasket = ['index', 'create', 'store', 'destroy'];
    Route::resource('basket', \App\Http\Controllers\Noise\Main\BasketController::class)
        ->only($methodsBasket)
        ->names('noise.main.basket')
        ->middleware('auth');
});

$groupDataAdmin = [
    'namespace' => 'App\Http\Controllers\Noise\Admin',
    'prefix' => 'noise/admin',
];
Route::group($groupDataAdmin, function () {
    //Для администратора
    $methods = ['index', 'edit', 'update', 'destroy', 'approve'];
    Route::resource('sources', \App\Http\Controllers\Noise\Admin\NoiseSourceController::class)
        ->only($methods)
        ->names('noise.admin.sources')
        ->middleware('auth');
    Route::patch(
        'approve/{id_file_path}',
        [\App\Http\Controllers\Noise\Admin\NoiseSourceController::class, 'approve']
    )->name('noise.admin.sources.approve')
     ->middleware('auth');
});
