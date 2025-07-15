<?php

namespace Models;

use Config\Database;
use PDO;
use PDOException;

class Product extends Database{
    // ------------------------------C---------------------------
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
            echo $title." Added for a price of ".$price."Taka";
        }else{
            echo "product insertion failed";
        }
    }
    // ------------------------------R---------------------------
    public function view(){
        $conn = $this->connect();
        $query = " SELECT * from Products";
        $stmt = $conn->prepare($query);
        $results = $stmt -> execute();

        if($results){
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }
    }

    // ------------------------------u---------------------------
    public function update($id, $data){
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
        return $stmt->execute($param);
    }


    public function delete($id){
        $conn = $this->connect();
        $query = "DELETE FROM products WHERE id = :id;";
        $stmt = $conn->prepare($query);
        $delete = $stmt->execute([
            "id" => $id
        ]);
    }
}