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
$router->group(['prefix' => 'group', 'middleware' => ['auth', 'role:users']], function () use ($router) {
    $router->post('', 'GroupController@store');
    $router->put('{id:[0-9]+}', 'GroupController@update');
    $router->get('', 'GroupController@index');
    $router->get('{id:[0-9]+}', 'GroupController@show');
    $router->delete('{id:[0-9]+}[/{method}]', 'GroupController@destroy');
    $router->group(['prefix' => 'trash'], function () use ($router) {
        $router->get('', 'GroupController@getTrashed');
        $router->put('{id:[0-9]+}', 'GroupController@restoreTrashed');
    });
    $router->group(['prefix' => '{code}/faq'], function () use ($router) {
        $router->post('', 'FaQController@store');
        $router->put('{id:[0-9]+}', 'FaQController@update');
        $router->get('', 'FaQController@index');
        $router->get('{id:[0-9]+}', 'FaQController@show');
        $router->delete('{id:[0-9]+}', 'FaQController@destroy');
        $router->group(['prefix' => '{id:[0-9]+}/answer_question'], function () use ($router) {
            $router->post('', 'FaQController@storeAQ');
            $router->get('{aq:[0-9]+}', 'FaQController@showAQ');
            $router->put('{aq:[0-9]+}', 'FaQController@updateAQ');
            $router->delete('{aq:[0-9]+}', 'FaQController@destroyAQ');
        });
    });
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
        $router->put('{id:[0-9]+}', 'PlanController@update');
        $router->get('', 'PlanController@index');
        $router->get('{id:[0-9]+}', 'PlanController@show');
        $router->delete('{id:[0-9]+}', 'PlanController@destroy');
    });
});
