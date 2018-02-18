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
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');
Route::post('/botman/init', 'BotManController@init');
Route::match(['get', 'post'], '/shot', 'ShotController@handle');
Route::get('/shot/tinker', 'ShotController@tinker');
Route::match(['get', 'post'],'/shot/init', 'ShotController@init');
Route::match(['get', 'post'], '/sample', 'SampleController@handle');
Route::get('/sample/tinker', 'SampleController@tinker');
Route::match(['get', 'post'],'/sample/init', 'SampleController@init');
