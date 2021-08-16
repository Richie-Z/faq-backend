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

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->group(['middleware' => ['auth', 'role:users']], function () use ($router) {
        $router->delete('logout', 'AuthController@logout');
        $router->get('detail', 'AuthController@detail');
    });
});

$router->group(['prefix' => 'admin'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'Admin\AuthController@login');
        $router->group(['middleware' => ['auth', 'role:admin']], function () use ($router) {
            $router->delete('logout', 'Admin\AuthController@logout');
            $router->get('detail', 'Admin\AuthController@detail');
        });
    });
    $router->group(['prefix' => 'plan', 'middleware' => ['auth', 'role:admin']], function () use ($router) {
        $router->post('', 'Admin\PlanController@store');
    });
});
