<?php
/*
 * Home Page
 */
Route::redirect('/', 'cp');
/*
 * Observer
 */
Route::get('observer', 'ObserverController@index')->name('observer');
/*
 * API
 */
Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
    Route::group(['middleware' => 'role:3'], function () {
        Route::resource('persons', 'PersonsController')->except(['create', 'edit']);
    });
    Route::group(['middleware' => 'role:3'], function () {
        Route::resource('divisions', 'DivisionsController')->except(['create', 'edit']);
    });
    Route::group(['middleware' => 'role:3'], function () {
        Route::resource('photos', 'PhotosController')->only(['store', 'destroy']);
    });
});
/*
 * Auth
 */
Auth::routes(['verify' => true]);
Route::get('register/{referral_code}', 'Auth\RegisterController@showRegistrationForm');
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
    Route::get('classes', 'DivisionsController@classes')->name('classes')->middleware('role:3');
    Route::get('persons', 'PersonsController@page')->name('persons')->middleware('role:3');

});
/*
 * Development
 */
Route::get('dev', 'DevController@index');
/*
 * Server
 */
Route::post('server', 'ServersController@index');
/*
 * Users
 */
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::post('token', 'UsersController@token');
    Route::get('organizations', 'UsersController@getOrganizations');
});
Route::get('users/notification/{hash}', 'UsersController@notification');
/*
 * Util
 */
Route::group(['prefix' => 'util', 'as' => 'util.'], function () {
    Route::post('save_errors', 'UtilsController@saveErrors');
    Route::post('card_problem', 'UtilsController@cardProblem');
});
