<?php

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
})->name('home');

Route::get('/protected', ['middleware' => 'auth', function() {
    $user = Auth::user();
    return view('protected')->with(compact('user'));
}])->name('protected');

Route::get('login/azure', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('login/azure/callback', 'Auth\LoginController@handleProviderCallback');
