<?php

namespace Models;

use Config\Database;

class Product extends Database{
    public function create($title, $price){
        $conn = $this->connect();
        $query="INSERT INTO products (title, price)
        VALUES (:title, :price);
        ";
        $stmt = $conn->prepare($query);
        $insertion = $stmt->execute([
            "title" => $title,
            "price" => $price
        ]);
        if($insertion == TRUE){
            echo $title."Added for a price of ".$price."Taka";
        }else{
            echo "product insertion failed";
        }
    }
}