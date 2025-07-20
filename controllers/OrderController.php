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


    // Function to check access and authentication
    private function authorize(array $allowedRoles) {
        $role = $this->authentication->decodeToken('role');

        if (in_array($role, $allowedRoles)) {
            return true;
        } else if (!$role) {
            Response::json(['error' => 'Login First'], 401);
            exit();
        }

        Response::json(['error' => 'Access Denied'], 403);
        exit();
    }


    public function createOrder()
    {
        $this->authorize(['Customer']);
        $data = Response::requestBody();
        $userId = $this->authentication->decodeToken('user_id');

        if (!isset($data['items']) || !is_array($data['items'])) {
            return Response::json(['error' => 'Order items required'], 400);
        }

        foreach ($data['items'] as $item) {
            if (!isset($item['productId'], $item['quantity'], $item['price'])) {
                return Response::json(['error' => 'Each item must have productId, quantity and price'], 400);
            }
        }

        $orderId = $this->orderModel->createOrderWithItems($userId, $data['items']);

        if ($orderId) {
            return Response::json(['message' => 'Order Created Successfully', 'orderId' => $orderId], 201);
        } else {
            return Response::json(['error' => 'Order Creation Failed'], 500);
        }
    }

    public function getAllOrders()
    {
        $this->authorize(['Admin']);
        $orders = $this->orderModel->getAllOrders();
        return Response::json(['data' => $orders]);
    }





    public function getOrderById($id)
    {
        $this->authorize(['Admin']);
        $order = $this->orderModel->getOrderById($id);

        if ($order) {
            return Response::json(['data' => $order]);
        } else {
            return Response::json(['error' => 'Order Not Found'], 404);
        }
    }

    public function getMyOrders()
    {
        $this->authorize(['Customer']);
        $userId = $this->authentication->decodeToken('user_id');
        $orders = $this->orderModel->getMyOrders($userId);
        return Response::json(['data' => $orders]);
    }

    public function deleteOrder($id)
    {
        $this->authorize(['Customer', 'Admin']);
        $deleted = $this->orderModel->deleteOrder($id);

        if ($deleted > 0) {
            return Response::json(['message' => 'Order Deleted Successfully']);
        } else {
            return Response::json(['error' => 'Order Not Found'], 404);
        }
    }
     // These two bellow functions were showing success, though the rows were not updated.
    public function updateOrderStatus($id)
    {
        $this->authorize(['Admin']);
        $data = Response::requestBody();

        if (!isset($data['status'])) {
            return Response::json(['error' => 'Status is required'], 400);
        }

        $allowedStatuses = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($data['status'], $allowedStatuses)) {
            return Response::json(['error' => 'Invalid status'], 400);
        }

        $updated = $this->orderModel->updateOrderStatus($id, $data['status']);

        if ($updated > 0) {
            return Response::json(['message' => 'Order Status Updated']);
        } else {
            return Response::json(['error' => 'Order Not Found or Status Unchanged'], 400);
        }
    }
}