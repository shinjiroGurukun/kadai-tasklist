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
// index: showの補助ページ

Route::get('/', 'tasksController@index');

Route::resource('task', 'tasksController');

// ユーザー登録
Route::get('signup','Auth\RegisterController@showRegister')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');