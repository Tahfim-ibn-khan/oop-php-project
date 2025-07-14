<?php

namespace Controllers;

use GrahamCampbell\ResultType\Success;
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


    public function show(){
        $products = new Product();
        $items= $products->view();

        return Response :: json([
            'message' => "Products Found",
            'data' => $items
        ],200);
    }

    public function update(){
        $data = json_decode(file_get_contents('php://input'),true);
        if(!isset($data['id'])){
            return Response::json(['error' => 'ID must be given'],400);
        }

        $id=$data['id'];
        unset($data['id']);

        if(empty($data)){
            return Response::json(['error' => 'No data provided'],400);
        }

        $product = new Product();

        $succes = $product->update($id, $data);



        if($succes){
            return Response::json(["Data Updated Successfully"]);
        }else{
            return Response::json(["Data Update Failed"],500);
        }
    }
}