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
$router->group(['prefix' => 'account', 'middleware' => ['auth', 'role:users']], function () use ($router) {
    $router->post('', 'AccountController@store');
    $router->put('', 'AccountController@update');
});
$router->group(['prefix' => 'admin', 'namespace' => 'Admin'], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthController@login');
        $router->group(['middleware' => ['auth', 'role:admin']], function () use ($router) {
            $router->delete('logout', 'AuthController@logout');
            $router->get('detail', 'AuthController@detail');
        });
    });
    $router->group(['prefix' => 'plan', 'middleware' => ['auth', 'role:admin']], function () use ($router) {
        $router->post('', 'PlanController@store');
        $router->put('', 'PlanController@update');
        $router->get('', 'PlanController@index');
        $router->delete('', 'PlanController@destroy');
    });
});
