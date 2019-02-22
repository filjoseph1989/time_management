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

Route::get('/', function() {
    return redirect()->to('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'projects'], function() {
    Route::get('/', 'ProjectsTimerController@index');
    Route::post('/', 'ProjectController@store');
    Route::post('/{id}/timers/stop', 'TimerController@stopRunning');
    Route::post('/{id}/timers', 'TimerController@store');
    Route::get('/timers/active', 'TimerController@running');
    Route::get('/timer/gettoday', 'TimerController@getToday');
    Route::post('/project/filter', 'ProjectController@filter');
    Route::get('/get/team', 'TeamController@getTeams');
    Route::get('/get/project/clients', 'ProjectClientController@getClients');
    Route::get('/teamgroup/get/team/members/{id}', 'TeamGroupController@getTeamMembers');

    Route::resources([
        'project'       => 'ProjectController',
        'projecttimer'  => 'ProjectsTimerController',
        'timer'         => 'TimerController',
        'client'        => 'ClientController',
        'projectclient' => 'ProjectClientController',
        'projectteam'   => 'ProjectTeamController',
        'user'          => 'UserController',
        'teamgroup'     => 'TeamGroupController',
    ]);
});

Route::get('/{any}', 'HomeController@index')->where('any', '.*');
