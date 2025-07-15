<?php
use Core\Router;
use Controllers\ProductController;
use Controllers\UserController;
use Models\Product;
use Models\User;
use Helpers\Authentication;

$productModel = new Product();
$productController = new ProductController($productModel);

$userModel = new User();
$authentication = new Authentication();
$userController = new UserController($userModel, $authentication);

$router = new Router();

// ----------------------Products Module-------------------------
$router->post('/products', [$productController, 'createProduct']);
$router->get('/products', [$productController, 'getAllList']);
$router->get('/products/{id}', [$productController, 'getById']);
$router->patch('/products/{id}', [$productController, 'updateProduct']);
$router->delete('/products/{id}', [$productController, 'deleteProduct']);

// ------------------------User Module-------------------------
$router->post('/users/register', [$userController, 'register']);
$router->post('/users/login', [$userController, 'login']);

$router->resolve($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
