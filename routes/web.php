<?php

use App\Http\Controllers\LoginController;
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

Route::get('/login/google', [LoginController::class, 'redirectToGoogleProvider']);

Route::get('login/google/callback', [LoginController::class, 'handleProviderGoogleCallback']);

Route::get('/drive', 'DriveController@getDrive'); // retreive folders

Route::get('/drive/upload', 'DriveController@uploadFile'); // File upload form

Route::post('/drive/upload', 'DriveController@uploadFile'); // Upload file to Drive from Form

Route::get('/drive/create', 'DriveController@create'); // Upload file to Drive from Storage

Route::get('/drive/delete/{id}', 'DriveController@deleteFile'); // Delete file or folder