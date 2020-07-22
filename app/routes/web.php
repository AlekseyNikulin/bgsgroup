<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('access-api-key/{api_token}', ['as' => 'access-api-key', 'uses' => 'Users\\UserController@accessApiKeyCreate']);

$router->group(['prefix'=>'api','middleware' => 'access-api-key'], function () use($router){

    /**
     * @Description Events list
     */
    $router->get('events',['as' => 'events', 'uses' => 'Events\\EventsController@showAll']);

    $router->post('events/{event_id}/user/create', 'Events\\UsersEventsController@create');

    $router->get('events/user/show','Events\\UsersEventsController@show');

    $router->delete('events/{event_id}/user/{user_id}','Events\\UsersEventsController@delete');

    $router->post('events/{event_id}/user/{user_id}/update','Events\\UsersEventsController@update');

    $router->get('events/{event_id}/user/list','Events\\UsersEventsController@list');
});

