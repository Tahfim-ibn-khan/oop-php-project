<?php
namespace Models;

use Config\Database;
use PDO;

class Order extends Database {

    private function getConnection() {
        return $this->connect();
    }

    // ---------------- C -----------------
    public function createOrder($customerId, $productId, $quantity){
        $conn = $this->getConnection();
        $query = "INSERT INTO orders (customer_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        try {
            $stmt->execute([$customerId, $productId, $quantity]);
            return $conn->lastInsertId();
        } catch (\PDOException $e) {
            return false;
        }
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

    // public function getMyOrder($id){
    //     $conn = $this->getConnection();
    //     $query = "SELECT * FROM ORDERS WHERE customer_id = ? ORDER BY created_at DESC";
    //     $stmt = $conn->prepare($query);

    //     try {
    //         $stmt = $conn->prepare($query);
    //         $stmt->execute([$id]);
    //     } catch (\Throwable $th) {
    //         return false;
    //     }
    // }
}
