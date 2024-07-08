<?php

use App\Http\Controllers\CongressionalMinutesController;
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

Route::get('/', [App\Http\Controllers\HomeController::class, 'select_municipality'])->name('municipality');
Route::get('/members', [App\Http\Controllers\HomeController::class, 'members'])->name('members');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->name('search');
Route::post('/search_members', [App\Http\Controllers\HomeController::class, 'search_members'])->name('search_members');
Route::get('/members/show/{id}', [App\Http\Controllers\HomeController::class, 'show'])->name('show');
