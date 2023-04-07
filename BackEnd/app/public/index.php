<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the products endpoint
$base = '/api';

$router->get($base.'/ads', 'AdController@getAll');
$router->get($base.'/ads/(\d+)', 'AdController@getOne');
$router->post($base.'/ads', 'AdController@create');
$router->put($base.'/ads/(\d+)', 'AdController@update');
$router->delete($base.'/ads/(\d+)', 'AdController@delete');
$router->get($base.'/ads/user', 'AdController@getAdsByUser');
$router->post($base.'/ads/checkout', 'AdController@checkOut');

// routes for the users endpoint
$router->post($base.'/users/login', 'UserController@login');
$router->get($base.'/users', 'UserController@getAll');
$router->post($base.'/users/register', 'UserController@create');
$router->delete($base.'/users/(\d+)', 'UserController@delete');


// Run it!
$router->run();