<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// API Routes
$router->group(['namespace' => 'Apis', 'prefix' => 'api'], function ($router) {
    // Store New Photo
    $router->post('photos', 'PhotoApi@store');
});

// Controller Routes
$router->group(['namespace' => 'Controllers'], function ($router) {
    // Upload Photo
    $router->post('photos/upload', 'PhotoController@upload');
    $router->get('photos/upload', 'PhotoController@uploadForm');

    // Show Photo
    $router->get('photos/{id}', 'PhotoController@show');
});

// Welcome Page
$router->get('/', function () {
    return view('welcome');
});
