<?php

namespace Controllers;

use GrahamCampbell\ResultType\Success;
use Models\Product;
use Helpers\Response;

class ProductController
{

    // Dependency Injection
    private $productModel;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    public function store()
    {

        $data = json_decode(file_get_contents('php://input'), true); // findout alternate solution

        if (!isset($data['title'], $data['price'])) {
            return Response::json(['error' => 'invalid Input', 422]);
        }

        //$product = new Product(); this is not good practice and findout why and write better solution
        $id = $this->productModel->create($data['title'], $data['price']); // Need few more info
        return Response::json([
            'message' => "Product Created",
            'id' => $id
        ], 201);
    }


    public function getAllList()
    { // getAllList()

        $items = $this->productModel->view();

        return Response::json([
            'message' => "Products Found",
            'data' => $items
        ], 200);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true); // need better something
        if (!isset($data['id'])) { //id should come from URL params
            return Response::json(['error' => 'ID must be given'], 400);
        }

        $id = $data['id'];
        unset($data['id']);

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


    public function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'])) { // take from url
            return Response::json([
                'error' => 'No id provided'
            ], 500);
        }

        // remove all relational data at a time.

        if ($this->productModel->delete($data['id'])) {
            return Response::json(['Product is deleted succcessfully.']);
        } else {
            return Response::json(['Product is deletion Failed.']);
        }
    }
}
