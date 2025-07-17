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
        // $role = $this->authentication->decodeToken('role');
        // if($role == 'Admin' || 'Customer');
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


    public function getMyOrders() {
        $userId = $this->authentication->decodeToken('user_id');
        echo $userId;
        $orders = $this->orderModel->getMyOrders($userId);
        return Response::json(['data' => $orders]);
    }

    // These two bellow functions were showing success, though the rows were not updated.
    public function updateOrder($id) {
        $data = Response::requestBody();

        if (!isset($data['quantity'])) {
            return Response::json(['error' => 'Quantity is required'], 400);
        }

        $updated = $this->orderModel->updateOrderQuantity($id, $data['quantity']);

        if ($updated > 0) {
            return Response::json(['message' => 'Order Quantity Updated']);
        } else {
            return Response::json(['error' => 'Order Update Failed'], 500);
        }
    }

    public function deleteOrder($id) {
        $deleted = $this->orderModel->deleteOrder($id);

        if ($deleted > 0) {
            return Response::json(['message' => 'Order Deleted Successfully']);
        } else {
            return Response::json(['error' => 'Order Deletion Failed'], 500);
        }
    }
}
