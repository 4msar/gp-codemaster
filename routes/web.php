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

$router->group([ 'prefix' => 'auth' ], function ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('register', 'AuthController@register');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
});

$router->group([ 'prefix' => 'app', 'middleware' => 'auth:api' ], function($router){
    // Customers Related Paths.
    $router->get('customers', 'CustomerController@index');
    $router->post('customers', 'CustomerController@store');
    $router->get('customers/{customer}', 'CustomerController@show');
    $router->put('customers/{customer}', 'CustomerController@update');
    $router->delete('customers/{customer}', 'CustomerController@destroy');
    
    // Customers Related Paths.
    $router->get('rooms', 'RoomController@index');
    $router->post('rooms', 'RoomController@store');
    $router->get('rooms/{room}', 'RoomController@show');
    $router->put('rooms/{room}', 'RoomController@update');
    $router->delete('rooms/{room}', 'RoomController@destroy');

    // Bookings Related Paths.
    $router->get('bookings', 'BookingController@index');
    $router->post('bookings', 'BookingController@book');
    $router->put('bookings/{booking}', 'BookingController@update');
    $router->patch('bookings/{booking}/checkin', 'BookingController@checkIn');
    $router->patch('bookings/{booking}/checkout', 'BookingController@checkOut');

    // Payment Related Routes
    $router->get('payments', 'PaymentController@index');
    $router->post('payments', 'PaymentController@store');
    $router->get('payments/{payment}', 'PaymentController@show');
    $router->delete('payments/{payment}', 'PaymentController@destroy');
});