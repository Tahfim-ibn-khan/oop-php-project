<?php
namespace Models;

use Config\Database;
use PDO;
use PDOException;

class Product extends Database{
    // ------------------------------C---------------------------
    public function createProduct($title, $description, $price, $stock_quantity, $image_url, $is_active, $category){
        $conn = $this->connect();
        $query = "INSERT INTO products (title, description, price, stock_quantity, image_url, is_active, category)
                VALUES (:title, :description, :price, :stock_quantity, :image_url, :is_active, :category);";

        $stmt = $conn->prepare($query);

        $insertion = $stmt->execute([
            "title" => $title,
            "description" => $description,
            "price" => $price,
            "stock_quantity" => $stock_quantity,
            "image_url" => $image_url,
            "is_active" => $is_active,
            "category" => $category
        ]);

        return $insertion ? $conn->lastInsertId() : false;
    }

    // ------------------------------R---------------------------
    public function getAllList(){
        $conn = $this->connect();
        $query = " SELECT * From Products;";
        $stmt = $conn->prepare($query);
        $results = $stmt -> execute();

        if($results){
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
    }

    public function getById($id){
        $conn = $this->connect();
        $query = " SELECT * from Products WHERE id = :id";
        $stmt = $conn->prepare($query);
        $results = $stmt -> execute(
            ['id' => $id]
        );

        if($results){
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // ------------------------------u---------------------------
    public function updateProduct($id, $data){
        $conn = $this->connect();
        $fields=[];
        $param=[];
        foreach($data as $key=>$value){
            $fields[]="$key = :$key";
            $param[$key] = $value;
        }
        $param['id'] = $id;

        if(empty($fields)){
            return false;
        }
        $query = "UPDATE products SET ".implode(', ', $fields)." WHERE id = :id;";
        $stmt = $conn->prepare($query);
        $stmt->execute($param);
        return $stmt->rowCount();
    }

    public function deleteProduct($id){
        $conn = $this->connect();
        $query = "DELETE FROM products WHERE id = :id;";
        $stmt = $conn->prepare($query);
        $stmt->execute(["id" => $id]);
        return $stmt->rowCount();
    }

}