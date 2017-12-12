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
Route::get('/', 'HomeController@index');
Route::post('/files/get', 'HomeController@listFiles');
Route::post('/files/save', 'HomeController@saveFiles');
Route::post('/files/upload', 'HomeController@uploadFiles');
Route::post('/lines/get', 'HomeController@listLines');
