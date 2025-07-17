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
        }
        else if(!$role){
            Response::json(['error' => 'Login First'], 401);
            exit();
        }

        Response::json(['error' => 'Access Denied'], 403);
        exit;
    }

    public function createOrder()
    {
        $this->authorize(['Customer']);

        $data = Response::requestBody();
        $userId = $this->authentication->decodeToken('user_id');

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



    public function getMyOrders() {
        $this->authorize(['Customer']);
        $userId = $this->authentication->decodeToken('user_id');
        $orders = $this->orderModel->getMyOrders($userId);
        return Response::json(['data' => $orders]);
    }

    // These two bellow functions were showing success, though the rows were not updated.
    public function updateOrder($id) {
        $this->authorize(['Customer']);

        $data = Response::requestBody();

        if (!isset($data['quantity'])) {
            return Response::json(['error' => 'Quantity is required'], 400);
        }

        $updated = $this->orderModel->updateOrderQuantity($id, $data['quantity']);

        if ($updated > 0) {
            return Response::json(['message' => 'Order Quantity Updated']);
        } else {
            return Response::json(['error' => 'Order Not Found or Quantity Unchanged'], 400);
        }
    }

    public function deleteOrder($id) {
        $this->authorize(['Customer', 'Admin']);

        $deleted = $this->orderModel->deleteOrder($id);

        if ($deleted > 0) {
            return Response::json(['message' => 'Order Deleted Successfully']);
        } else {
            return Response::json(['error' => 'Order Not Found'], 404);
        }
    }
}



    // $check = $this->authentication->decodeToken('role');
    // if($check == 'Customer' || 'Admin'){
    //     // Code here
    // }else if(!($check == 'Customer' || 'Admin')){
    //     return Response::json(['message' => 'Access denied'],403);
    // }
    // else{
    //     return Response::json(['message' => $check], 401);
    // }