<?php

declare(strict_types=1);

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

// MongoDB Routes
Router::addGroup('/api', function () {
    Router::get('/users', 'App\Controller\UserController@index');
    Router::get('/users/{id}', 'App\Controller\UserController@show');
    Router::post('/users', 'App\Controller\UserController@store');
    Router::put('/users/{id}', 'App\Controller\UserController@update');
    Router::delete('/users/{id}', 'App\Controller\UserController@delete');
});
