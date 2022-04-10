<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Authorization');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

//routes for the posts endpoint
$router->get('/posts', 'PostController@getAll');
$router->get('/posts/users/(\d+)', 'PostController@getByUserId');
$router->put('/posts/(\d+)', 'PostController@update');
$router->delete('/posts/(\d+)', 'PostController@deleteOne');

//routes for the users endpoint
$router->post('/users/login', 'UserController@login');

// Run it!
$router->run();