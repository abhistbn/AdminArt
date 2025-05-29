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

$router->group(['prefix' => 'api'], function () use ($router) {
    // Rute untuk Kategori
    $router->get('categories', 'CategoryController@index');
    $router->post('categories', 'CategoryController@store');
    // Anda bisa tambahkan PUT dan DELETE untuk kategori di sini jika perlu nanti

    // Rute untuk Artikel (JSON API)
    $router->get('articles', 'ArticleController@index');      // GET semua artikel (untuk list di article_management.html)
    $router->post('articles', 'ArticleController@store');     // POST artikel baru (digunakan oleh tambah_artikel.html)
    $router->get('articles/{id}', 'ArticleController@show');   // GET artikel spesifik (digunakan oleh article_manager.js saat edit)
    $router->put('articles/{id}', 'ArticleController@update'); // PUT (update) artikel (digunakan oleh modal edit jika masih ada)
    $router->delete('articles/{id}', 'ArticleController@destroy'); // DELETE artikel (digunakan oleh article_management.html)
});


// ======================================================================
// GRUP RUTE ADMIN (UNTUK SERVER-SIDE RENDERED VIEWS - BLADE)
// ======================================================================
$router->group(['prefix' => 'admin' /*, 'middleware' => 'auth' // Tambahkan middleware auth jika perlu nanti */], function () use ($router) {
    
    // Rute untuk menampilkan form tambah artikel (Blade)
    $router->get('/articles/create', 'ArticleEditController@create');
    
    // Rute untuk menyimpan artikel baru dari form Blade (jika Anda membuat form tambah versi Blade)
    // Jika tambah artikel tetap menggunakan tambah_artikel.html (via API JSON), rute POST ini di grup admin tidak wajib.
    // Namun, jika Anda ingin form Blade di /admin/articles/create juga bisa menyimpan, Anda perlu method store di ArticleEditController.
    $router->post('/articles', 'ArticleEditController@store');        
    
    // Rute untuk menampilkan form edit artikel (Blade)
    // Ini yang akan dipanggil oleh tombol "Edit" dari article_management.html
    $router->get('/articles/{id}/edit', 'ArticleEditController@edit'); 
    
    // Rute untuk mengupdate artikel dari form edit Blade
    $router->put('/articles/{id}', 'ArticleEditController@update');      
    
    // Anda bisa menambahkan rute lain di sini untuk admin, misalnya:
    // $router->get('/dashboard', 'AdminDashboardController@index');
});