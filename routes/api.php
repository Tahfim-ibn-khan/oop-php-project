<?php

use Core\Router;
use Controllers\ProductController;
use Controllers\UserController;
use Controllers\OrderController;
use Models\Product;
use Models\User;
use Models\Order;
use Helpers\Authentication;

$productModel = new Product();
$userModel = new User();
$orderModel = new Order();
$authentication = new Authentication();

$productController = new ProductController($productModel, $authentication);
$userController = new UserController($userModel, $authentication);
$orderController = new OrderController($orderModel, $authentication);

$router = new Router();

$router->post('/products', [$productController, 'createProduct']);
$router->get('/products', [$productController, 'getAllList']);
$router->get('/products/{id}', [$productController, 'getById']);
$router->patch('/products/{id}', [$productController, 'updateProduct']);
$router->delete('/products/{id}', [$productController, 'deleteProduct']);

$router->post('/users/register', [$userController, 'register']);
$router->post('/users/login', [$userController, 'login']);
$router->get('/users/profile', [$userController, 'viewProfile']);

$router->post('/orders', [$orderController, 'createOrder']);
$router->get('/orders', [$orderController, 'getAllOrders']);
$router->get('/orders/{id}', [$orderController, 'getOrderById']);
// Latest Works
$router->get('/my-orders', [$orderController, 'getMyOrders']);
$router->delete('/orders/{id}', [$orderController, 'deleteOrder']);
$router->patch('/orders/{id}/status', [$orderController, 'updateOrderStatus']);



$router->resolve($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
