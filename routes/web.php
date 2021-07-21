<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TableController;
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

Route::get('/add-org', function () {
    return view('addGroup');
});

Route::post('/add-org', [TableController::class,'addGroup'])->name('addGroup');
Route::post('/update-org', [TableController::class,'updateGroup'])->name('updateGroup');
Route::get('/delete-org/{id}', [TableController::class,'deleteGroup'])->name('deleteGroup');
Route::get('/org-list',[TableController::class, 'index']);
