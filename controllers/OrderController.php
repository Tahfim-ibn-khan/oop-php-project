<?php

namespace Controllers;

use Models\Order;
use Helpers\Response;
use Helpers\Authentication;

class OrderController
{

    private $orderModel;
    private $authentication;

    public function __construct(Order $orderModel, Authentication $authentication)
    {
        $this->orderModel = $orderModel;
        $this->authentication = $authentication;
    }


    public function createOrder()
    {
        $data = Response::requestBody();
        $field = 'user_id';
        $userId = $this->authentication->decodeToken($field);

        if (!isset($data['productId'], $data['quantity'])) {
            return Response::json(['error' => 'Product ID and quantity required'], 400);
        }

        $orderId = $this->orderModel->createOrder($userId, $data['productId'], $data['quantity']);

        if ($orderId) {
            return Response::json(['message' => 'Order Created Successfully', 'orderId' => $orderId], 201);
        } else {
            return Response::json(['error' => 'Order Creation Failed'], 500);
        }
    }




    public function getAllOrders()
    {
        $orders = $this->orderModel->getAllOrders();
        return Response::json(['data' => $orders]);
    }



    public function getOrderById($id)
    {
        $order = $this->orderModel->getOrderById($id);

        if ($order) {
            return Response::json(['data' => $order]);
        } else {
            return Response::json(['error' => 'Order Not Found'], 404);
        }
    }


    // public function getMyOrder($id){
    //     $query = ''
    // }
}
