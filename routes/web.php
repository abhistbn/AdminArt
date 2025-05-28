<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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


// Admin Routes
$router->group(['prefix' => 'admin'], function () use ($router) {
    
    // Articles Management
    $router->get('/articles', 'ArticleEditController@index');
    $router->get('/articles/create', 'ArticleEditController@create');
    $router->post('/articles', 'ArticleEditController@store');
    $router->get('/articles/{id}', 'ArticleEditController@show');
    $router->get('/articles/{id}/edit', 'ArticleEditController@edit');
    $router->put('/articles/{id}', 'ArticleEditController@update');
    $router->delete('/articles/{id}', 'ArticleEditController@destroy');
    $router->get('/articles/{id}/preview', 'ArticleEditController@preview');
    
});