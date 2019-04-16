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
 * API
 */
Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
    Route::group(['middleware' => 'role:3'], function () {
        Route::resource('persons', 'PersonsController',
            ['except' => ['create', 'edit']]);
    });
    Route::group(['middleware' => 'role:3'], function () {
        Route::resource('divisions', 'DivisionsController',
            ['except' => ['create', 'edit']]);
    });
});
/*
 * Auth
 */
Auth::routes(['verify' => true]);
/*
 * Cards
 */
Route::group(['prefix' => 'cards', 'as' => 'cards.'], function () {
    Route::get('get_list', 'CardsController@getListOfUnknownCards');
    Route::get('get_list/{person_id}', 'CardsController@getListByPerson');
    Route::post('holder', 'CardsController@setHolder');
    Route::post('delete', 'CardsController@delete');
});
/*
 * Controllers
 */
Route::group(['prefix' => 'controllers', 'as' => 'controllers.'], function () {
    Route::get('get_list', 'ControllersController@getList');
    Route::get('set_door_params/{controller_id}/{open_time}', 'ControllersController@setDoorParams');
    Route::get('clear/{controller_id}', 'ControllersController@clear');
    Route::get('reload_cards/{controller_id}', 'ControllersController@reloadCards');
});
/*
 * Control Panel
 */
Route::group(['prefix' => 'cp', 'as' => 'cp.'], function () {
    Route::get('/', 'UsersController@index')->name('index');
    Route::get('/classes/{organization_id?}', 'DivisionsController@classes')->name('classes')->middleware('role:3');
    Route::get('/persons/{organization_id?}', 'PersonsController@page')->name('persons')->middleware('role:3');

});
/*
 * Development
 */
Route::get('dev', 'DevController@index');
/*
 * Photos
 */
Route::group(['prefix' => 'photos', 'as' => 'photos.', 'middleware' => 'role:3'], function () {
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
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::post('token', 'UsersController@token');
});
Route::get('users/notification/{hash}', 'UsersController@notification');
/*
 * Util
 */
Route::group(['prefix' => 'util', 'as' => 'util.'], function () {
    Route::post('save_errors', 'UtilsController@saveErrors');
    Route::post('card_problem', 'UtilsController@cardProblem');
});
