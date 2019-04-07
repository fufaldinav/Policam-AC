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
Route::get('/', 'ObserverController@index')->name('/');
/*
 * Auth
 */
Auth::routes(['verify' => true]);
/*
 * Cards
 */
Route::group(['prefix' =>'cards', 'as' => 'cards.'], function() {
    Route::get('get_list', 'CardsController@getListOfUnknownCards');
    Route::get('get_list/{person_id}', 'CardsController@getListByPerson');
    Route::post('holder', 'CardsController@setHolder');
    Route::post('delete', 'CardsController@delete');
});
/*
 * Controllers
 */
Route::group(['prefix' =>'controllers', 'as' => 'controllers.'], function() {
    Route::get('get_list', 'ControllersController@getList');
    Route::get('set_door_params/{controller_id}/{open_time}', 'ControllersController@setDoorParams');
    Route::get('clear/{controller_id}', 'ControllersController@clear');
    Route::get('reload_cards/{controller_id}', 'ControllersController@reloadCards');
});
/*
 * Control Panel
 */
Route::get('cp', 'UsersController@index')->name('cp');
/*
 * Development
 */
Route::get('dev', 'DevController@index');
/*
 * Divisions
 */
Route::group(['prefix' =>'divisions', 'as' => 'divisions.'], function() {
    Route::group(['middleware' => 'role:3'], function () {
        Route::get('classes', 'DivisionsController@classes')->name('classes');
        Route::post('save', 'DivisionsController@save');
        Route::post('delete', 'DivisionsController@delete');
    });
    Route::get('get_list', 'DivisionsController@getList');
});
/*
 * Persons
 */
Route::group(['prefix' =>'persons', 'as' => 'persons.'], function() {
    Route::group(['middleware' => 'role:3'], function () {
        Route::get('/{organization_id?}', 'PersonsController@index')->name('index');
        Route::get('add', 'PersonsController@add')->name('add');
        Route::get('edit', 'PersonsController@edit')->name('edit');
        Route::post('save/{person_id?}', 'PersonsController@save');
        Route::post('delete', 'PersonsController@delete');
    });
    Route::get('get/{person_id}', 'PersonsController@get');
    Route::get('get_by_card/{card_id}', 'PersonsController@getByCard');
    Route::get('get_list/{division_id}', 'PersonsController@getListByDivision');
});
/*
 * Photos
 */
Route::group(['prefix' =>'photos', 'as' => 'photos.', 'middleware' => 'role:3'], function() {
    Route::post('save', 'PhotosController@save');
    Route::post('delete', 'PhotosController@delete');
});
/*
 * Server
 */
Route::post('server', 'ServersController@index');
/*
 * Users
 */
Route::group(['prefix' =>'users', 'as' => 'users.'], function() {
    Route::post('token', 'UsersController@token');
});
Route::get('users/notification/{hash}', 'UsersController@notification');
/*
 * Util
 */
Route::group(['prefix' =>'util', 'as' => 'util.'], function() {
    Route::post('save_errors', 'UtilsController@saveErrors');
    Route::post('card_problem', 'UtilsController@cardProblem');
});
