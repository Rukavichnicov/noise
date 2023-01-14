<?php

use Illuminate\Support\Facades\Mail;
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
    Route::get(
        'search',
        [\App\Http\Controllers\Noise\Main\NoiseSourceController::class, 'search']
    )->name('noise.main.sources.search');

    $resourceMethodsBasket = ['index', 'store', 'destroy'];
    Route::resource('basket', \App\Http\Controllers\Noise\Main\BasketController::class)
        ->only($resourceMethodsBasket)
        ->names('noise.main.basket')
        ->middleware('auth');
    Route::get(
        'downloadReport',
        [\App\Http\Controllers\Noise\Main\BasketController::class, 'downloadReport']
    )->name('noise.main.basket.downloadReport')
        ->middleware('auth');
    Route::get(
        'downloadArchiveFile',
        [\App\Http\Controllers\Noise\Main\BasketController::class, 'downloadArchiveFile']
    )->name('noise.main.basket.downloadArchiveFile')
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
        ->middleware('verify.user.is.admin');
    Route::patch(
        'approve/{id_file_path}',
        [\App\Http\Controllers\Noise\Admin\NoiseSourceController::class, 'approve']
    )->name('noise.admin.sources.approve')
     ->middleware('verify.user.is.admin');
});
Route::get('send', [\App\Http\Controllers\MailController::class, 'send']);
