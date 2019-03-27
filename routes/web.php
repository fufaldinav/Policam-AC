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
Route::get('/', 'ObserverController@index')->name('/')->middleware('auth');
/*
 * Auth
 */
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
/*
 * Cards
 */
Route::group(['prefix' =>'cards', 'as' => 'cards.', 'middleware' => 'auth'], function() {
    Route::get('get_list/{person_id}', 'CardsController@getList')->name('get_list');
    Route::get('holder/{card_id}/{person_id}', 'CardsController@holder')->name('holder');
    Route::get('delete/{card_id}', 'CardsController@delete')->name('delete');
});
/*
 * Controllers
 */
Route::group(['prefix' =>'controllers', 'as' => 'controllers.', 'middleware' => 'auth'], function() {
    Route::get('set_door_params/{controller_id}/{open_time}', 'ControllersController@setDoorParams')->name('set_door_params');
    Route::get('clear/{controller_id}', 'ControllersController@clear')->name('clear');
    Route::get('reload_cards/{controller_id}', 'ControllersController@reloadCards')->name('reload_cards');
});
/*
 * Control Panel
 */
Route::get('cp', 'CpController@index')->name('cp')->middleware('auth');
/*
 * Development
 */
Route::get('dev', 'DevController@index')->name('dev')->middleware('auth');
/*
 * Divisions
 */
Route::group(['prefix' =>'divisions', 'as' => 'divisions.', 'middleware' => 'auth'], function() {
    Route::get('classes', 'DivisionsController@classes')->name('classes');
    Route::get('get_list', 'DivisionsController@getList')->name('get_list');
    Route::post('add', 'DivisionsController@add')->name('add');
    Route::get('delete/{division_id}', 'DivisionsController@delete')->name('delete');
});
/*
 * Persons
 */
Route::group(['prefix' =>'persons', 'as' => 'persons.', 'middleware' => 'auth'], function() {
    Route::get('add', 'PersonsController@add')->name('add');
    Route::get('edit', 'PersonsController@edit')->name('edit');
    Route::get('get/{person_id}', 'PersonsController@get')->name('get');
    Route::get('get_by_card/{card_id}', 'PersonsController@getByCard')->name('get_by_card');
    Route::get('get_list/{division_id}', 'PersonsController@getList')->name('get_list');
    Route::post('save/{person_id}', 'PersonsController@save')->name('save');
    Route::get('delete/{person_id}', 'PersonsController@delete')->name('delete');
});
/*
 * Photos
 */
Route::group(['prefix' =>'photos', 'as' => 'photos.', 'middleware' => 'auth'], function() {
    Route::post('save', 'PhotosController@save')->name('save');
    Route::get('delete/{photo_id}', 'PhotosController@delete')->name('delete');
});
/*
 * Server
 */
Route::post('server', 'ServerController@index')->name('server');
/*
 * Users
 */
Route::group(['prefix' =>'users', 'as' => 'users.', 'middleware' => 'auth'], function() {
    Route::get('token', 'UsersController@token')->name('token');
    Route::get('notification/{hash}', 'UsersController@notification')->name('notification');
});
/*
 * Util
 */
Route::group(['prefix' =>'util', 'as' => 'util.', 'middleware' => 'auth'], function() {
    Route::get('get_time', 'UtilsController@getTime')->name('get_time');
    Route::post('get_events', 'UtilsController@getEvents')->name('get_events');
    Route::post('save_errors', 'UtilsController@saveErrors')->name('save_errors');
    Route::post('card_problem', 'UtilsController@cardProblem')->name('card_problem');
});
