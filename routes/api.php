<?php

use Core\Router;
use Controllers\ProductController;

$router = new Router();
$router->post('/products', [ProductController::class, 'store']);
$router->get('/products', [ProductController::class, 'show']);
$router->patch('/products', [ProductController::class, 'update']);


$router->resolve($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
