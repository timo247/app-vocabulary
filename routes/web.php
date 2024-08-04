<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VocabularyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('home');
});
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [VocabularyController::class, 'index'])->name('home')->middleware('auth');
