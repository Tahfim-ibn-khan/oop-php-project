<?php

use Core\Router;
use Controllers\ProductController;

$router = new Router();
$router->post('/products', [ProductController::class, 'store']);
$router->resolve($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
