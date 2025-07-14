<?php
use Core\Router;
use Controllers\ProductController;
use Models\Product;

$productModel = new Product();
$productController = new ProductController($productModel);
$router = new Router();
$router->post('/products', [$productController, 'store']);
$router->get('/products', [$productController, 'getAllList']);
$router->patch('/products', [$productController, 'update']);
$router->delete('/products', [$productController, 'delete']);

$router->resolve($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
