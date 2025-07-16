<?php

namespace Controllers;

use Models\Product;
use Helpers\Response;
use Helpers\Authentication;

class ProductController
{

    private $productModel;
    private $authentication;

    public function __construct(Product $product, Authentication $authentication) {
        $this->productModel = $product;
        $this->authentication = $authentication;
    }

    private function getRequestData() {
        return Response::requestBody();
    }    

    public function createProduct()
    {
        $isAdmin = $this->authentication->verifyRole('Admin');
        if ($isAdmin !== true) {
            return $isAdmin;
        }
        $data = $this->getRequestData();

        if (!isset($data['title'], $data['price'])) {
            return Response::json(['error' => 'invalid Input', 422]);
        }

        //$product = new Product(); this is not good practice and find out why and write better solution

        $id = $this->productModel->create($data['title'], $data['description'] ?? null, $data['price'], $data['stock_quantity'] ?? null, $data['image_url'] ?? null, $data['is_active'] ?? true); // Need few more info
        return Response::json([
            'message' => "Product Created",
            'id' => $id
        ], 201);
    }


    public function getAllList()
    { // getAllList()


        $items = $this->productModel->read();

        return Response::json([
            'message' => "Products Found",
            'data' => $items
        ], 200);
    }


    public function getById($id)
    { // getAllList()

        $items = $this->productModel->readById($id);

        return Response::json([
            'message' => "Product Found",
            'data' => $items
        ], 200);
    }

    public function updateProduct($id)
    {
        $isAdmin = $this->authentication->verifyRole('Admin');
        if ($isAdmin !== true) {
            return $isAdmin;
        }

        $data = $this->getRequestData();
        if (!$id) { //id should come from URL params
            return Response::json(['error' => 'ID must be given'], 400);
        }

        if (empty($data)) {
            return Response::json(['error' => 'No data provided'], 400);
        }


        $succes = $this->productModel->update($id, $data);

        if ($succes) {
            return Response::json(["Data Updated Successfully"]);
        } else {
            return Response::json(["Data Update Failed"], 500);
        }
    }



    public function deleteProduct($id)
    {

        $isAdmin = $this->authentication->verifyRole('Admin');
        if ($isAdmin !== true) {
            return $isAdmin;
        }

        if (!$id) { // take from url
            return Response::json([
                'error' => 'No id provided'
            ], 500);
        }

        // remove all relational data at a time.


        if ($this->productModel->delete($id)) {
            return Response::json(['Product is deleted successfully.']);
        } else {
            return Response::json(['Product is deletion Failed.']);
        }
    }
}
