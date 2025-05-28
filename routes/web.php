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

// Grup Rute API kita
$router->group(['prefix' => 'api'], function () use ($router) {
    // Rute untuk Kategori
    $router->get('categories', 'CategoryController@index');
    $router->post('categories', 'CategoryController@store');

    // (Anda bisa menambahkan rute API lain di sini nanti)
});
