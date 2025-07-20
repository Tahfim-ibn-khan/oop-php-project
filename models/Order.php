<?php
namespace Models;

use Config\Database;
use PDO;

class Order extends Database {

    private function getConnection() {
        return $this->connect();
    }

    // ---------------- C -----------------
        public function createOrderWithItems($customerId, $items){
        $conn = $this->getConnection();

        $totalAmount = 0;
        foreach ($items as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        // insertion into orders
        $orderQuery = "INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'pending', NOW())";
        $stmt = $conn->prepare($orderQuery);
        $stmt->execute([$customerId, $totalAmount]);

        $orderId = $conn->lastInsertId();

        // inserting items into order_items
        $itemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
        $itemStmt = $conn->prepare($itemQuery);

        foreach ($items as $item) {
            $itemStmt->execute([
                $orderId,
                $item['productId'],
                $item['quantity'],
                $item['price']
            ]);
        }

        return $orderId;
    }

    public function getAllOrders(){
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM orders");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($orderId){
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMyOrders($customerId){
        $conn = $this->getConnection();

        $query = "
            SELECT o.id as order_id, o.total_amount, o.status, o.created_at,
                   i.product_id, i.quantity, i.price_at_purchase
            FROM orders o
            JOIN order_items i ON o.id = i.order_id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ";

        $stmt = $conn->prepare($query);
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderId, $status){
        $conn = $this->getConnection();
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $orderId]);
        return $stmt->rowCount();
    }

    public function deleteOrder($orderId){
        $conn = $this->getConnection();

        $stmt1 = $conn->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt1->execute([$orderId]);

        $stmt2 = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt2->execute([$orderId]);

        return $stmt2->rowCount();
    }
}
