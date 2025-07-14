<?php

namespace Controllers;

use Models\Product;
use Helpers\Response;

class ProductController{
    public function store(){
        $data = json_decode(file_get_contents('php://input'),true);

        if(!isset($data['title'], $data['price'])){
            return Response::json(['error'=>'invalid Input', 422]);
        }

        $product = new Product();
        $id= $product->create($data['title'], $data['price']);

        return Response::json([
            'message' =>"Product Created",
            'id' => $id
        ], 201);
    }
}