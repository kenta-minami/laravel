<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UNOfController;
 
Route::resource('tasks', TaskController::class);
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

Route::get('/list', [TodoListController::class, 'index']);

Route::get('/uno', [UNOfController::class, 'index'])->name('uno.index');
Route::post('/uno', [UNOfController::class, 'next'])->name('uno.next');

//test