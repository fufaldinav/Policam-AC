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

/*
 * Home Page
 */
Route::get('/', 'ObserverController@index');
/*
 * Auth
 */
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
/*
 * Cards
 */
Route::get('cards/get_list/{person_id}', 'CardsController@getList')->name('cards/get_list');
Route::get('cards/holder/{card_id}/{person_id}', 'CardsController@holder')->name('cards/holder');
Route::get('cards/delete/{card_id}', 'CardsController@delete')->name('cards/delete');
/*
 * Controllers
 */
Route::get('controllers/set_door_params/{controller_id}/{open_time}', 'ControllersController@setDoorParams')->name('controllers/set_door_params');
Route::get('controllers/clear/{controller_id}', 'ControllersController@clear')->name('controllers/clear');
Route::get('controllers/reload_cards/{controller_id}', 'ControllersController@reloadCards')->name('controllers/reload_cards');
/*
 * Control Panel
 */
Route::get('cp', 'CpController@index')->name('cp');
/*
 * Development
 */
Route::get('dev', 'DevController@index')->name('dev');
/*
 * Divisions
 */
Route::get('divisions/classes', 'DivisionsController@classes')->name('divisions/classes');
Route::get('divisions/get_list', 'DivisionsController@getList')->name('divisions/get_list');
Route::get('divisions/add', 'DivisionsController@add')->name('divisions/add');
Route::get('divisions/delete/{division_id}', 'DivisionsController@delete')->name('divisions/delete');
/*
 * Persons
 */
Route::get('persons/add', 'PersonsController@add')->name('persons/add');
Route::get('persons/edit', 'PersonsController@edit')->name('persons/edit');
Route::get('persons/get/{person_id}', 'PersonsController@get')->name('persons/get');
Route::get('persons/get_by_card/{card_id}', 'PersonsController@getByCard')->name('persons/get_by_card');
Route::get('persons/get_list/{division_id}', 'PersonsController@getList')->name('persons/get_list');
Route::post('persons/save/{person_id}', 'PersonsController@save')->name('persons/save');
Route::get('persons/delete/{person_id}', 'PersonsController@delete')->name('persons/delete');
/*
 * Photos
 */
Route::post('photos/save', 'PhotosController@save')->name('photos/save');
Route::get('photos/delete/{photo_id}', 'PhotosController@delete')->name('photos/delete');
/*
 * Server
 */
Route::post('server', 'ServerController@index')->name('server');
/*
 * Users
 */
Route::get('users/token', 'UsersController@token')->name('users/token');
Route::get('users/notification/{hash}', 'UsersController@notification')->name('users/notification');
/*
 * Util
 */
Route::get('util/get_time', 'UtilsController@getTime')->name('util/get_time');
Route::post('util/get_events', 'UtilsController@getEvents')->name('util/get_events');
Route::get('util/save_errors', 'UtilsController@saveErrors')->name('util/save_errors');
Route::get('util/card_problem', 'UtilsController@cardProblem')->name('util/card_problem');
