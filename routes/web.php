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

Route::get('/', 'ObserverController@index');

Route::get('persons/add', 'PersonsController@add')->name('persons/add');
Route::get('persons/edit', 'PersonsController@edit')->name('persons/edit');

Route::get('divisions/classes', 'DivisionsController@classes')->name('divisions/classes');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
