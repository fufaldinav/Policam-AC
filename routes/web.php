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
Route::get('/', 'ObserverController@index')->name('/')->middleware('auth', 'verified');
/*
 * Auth
 */
Auth::routes(['verify' => true]);
/*
 * Cards
 */
Route::group(['prefix' =>'cards', 'as' => 'cards.', 'middleware' => ['auth', 'verified']], function() {
    Route::get('get_list/{person_id?}', 'CardsController@getList')->name('get_list');
    Route::post('holder', 'CardsController@holder')->name('holder');
    Route::post('delete', 'CardsController@delete')->name('delete');
});
/*
 * Controllers
 */
Route::group(['prefix' =>'controllers', 'as' => 'controllers.', 'middleware' => ['auth', 'verified']], function() {
    Route::get('set_door_params/{controller_id}/{open_time}', 'ControllersController@setDoorParams')->name('set_door_params');
    Route::get('clear/{controller_id}', 'ControllersController@clear')->name('clear');
    Route::get('reload_cards/{controller_id}', 'ControllersController@reloadCards')->name('reload_cards');
});
/*
 * Control Panel
 */
Route::get('cp', 'UsersController@index')->name('cp')->middleware('auth', 'verified');
/*
 * Development
 */
Route::get('dev', 'DevController@index')->name('dev')->middleware('auth', 'verified');
Route::get('dev/test', 'DevController@test')->name('dev.test')->middleware('auth', 'verified');
/*
 * Divisions
 */
Route::group(['prefix' =>'divisions', 'as' => 'divisions.', 'middleware' => ['auth', 'verified']], function() {
    Route::get('classes', 'DivisionsController@classes')->name('classes')->middleware('role:3');
    Route::get('get_list', 'DivisionsController@getList')->name('get_list');
    Route::post('save', 'DivisionsController@save')->name('save');
    Route::post('delete', 'DivisionsController@delete')->name('delete');
});
/*
 * Persons
 */
Route::group(['prefix' =>'persons', 'as' => 'persons.', 'middleware' => ['auth', 'verified']], function() {
    Route::group(['middleware' => 'role:3'], function () {
        Route::get('add', 'PersonsController@add')->name('add');
        Route::get('edit', 'PersonsController@edit')->name('edit');
        Route::post('save/{person_id?}', 'PersonsController@save')->name('save');
        Route::post('delete', 'PersonsController@delete')->name('delete');
    });
    Route::get('get/{person_id}', 'PersonsController@get')->name('get');
    Route::get('get_by_card/{card_id}', 'PersonsController@getByCard')->name('get_by_card');
    Route::get('get_list/{division_id}', 'PersonsController@getList')->name('get_list');
});
/*
 * Photos
 */
Route::group(['prefix' =>'photos', 'as' => 'photos.', 'middleware' => ['auth', 'verified']], function() {
    Route::post('save', 'PhotosController@save')->name('save');
    Route::post('delete', 'PhotosController@delete')->name('delete');
});
/*
 * Server
 */
Route::post('server', 'ServersController@index')->name('server');
/*
 * Users
 */
Route::group(['prefix' =>'users', 'as' => 'users.', 'middleware' => ['auth', 'verified']], function() {
    Route::redirect('cp', '/cp')->name('users.cp');
    Route::get('notification/{hash?}', 'UsersController@notification')->name('notification');
    Route::post('token', 'UsersController@token')->name('token');
});
/*
 * Util
 */
Route::group(['prefix' =>'util', 'as' => 'util.', 'middleware' => ['auth', 'verified']], function() {
    Route::get('get_time', 'UtilsController@getTime')->name('get_time');
    Route::post('get_events', 'UtilsController@getEvents')->name('get_events');
    Route::post('save_errors', 'UtilsController@saveErrors')->name('save_errors');
    Route::post('card_problem', 'UtilsController@cardProblem')->name('card_problem');
});
