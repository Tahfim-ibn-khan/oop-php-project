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

    
    public function createProduct()
    {
        $this->authorize(['Admin']);
        $data = $this->getRequestData();

        // Validate required fields
        $requiredFields = ['title', 'price', 'category', 'stock_quantity', 'is_active'];
        $missingFields = [];

        // it must provide the mendatory fields
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            return Response::json([
                'error' => 'Missing required fields: ' . implode(', ', $missingFields)
            ], 422);
        }

        // There are limited number of categories
        if (isset($data['category'])) {
        $allowedCategories = ['Mobiles', 'Accessories', 'Chargers'];
        if (!in_array($data['category'], $allowedCategories)) {
            return Response::json(['error' => 'Invalid category.'], 422);
        }
        }


        // Validating price and stock_quantity as numeric
        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            return Response::json(['error' => 'Price must be a positive number.'], 422);
        }

        if (!is_numeric($data['stock_quantity']) || $data['stock_quantity'] < 0) {
            return Response::json(['error' => 'Stock quantity must be zero or more.'], 422);
        }

        // description and image link can be given later
        $description = $data['description'] ?? null;
        $imageUrl = $data['image_url'] ?? null;

        $id = $this->productModel->createProduct(
            $data['title'],
            $description,
            $data['price'],
            $data['stock_quantity'],
            $imageUrl,
            $data['is_active'],
            $data['category']
        );

        return Response::json([
            'message' => "Product Created",
            'id' => $id
        ], 201);
    }



    // Everyone can view all the products
    public function getAllList()
    { // getAllList()

        $items = $this->productModel->getAllList();
        if(empty($items)){
            return Response::json([
            'message' => "Products Not Found"
        ], 404);
        }

        return Response::json([
            'message' => "Products Found",
            'data' => $items
        ], 200);
    }


    public function getById($id)
    { // getAllList()
        // id must be numeric
        if (!is_numeric($id)) {
            return Response::json(['error' => 'Invalid id.'], 422);
        }

        $items = $this->productModel->getById($id);

        if(empty($items)){
            return Response::json([
            'message' => "Product Not Found"
        ], 404);
        }
        return Response::json([
            'message' => "Product Found",
            'data' => $items
        ], 200);
    }

    public function updateProduct($id)
    {
        // id must be numeric
        if (!is_numeric($id)) {
            return Response::json(['error' => 'Invalid id.'], 422);
        }
        $this->authorize(['Admin']);

        $data = $this->getRequestData();
        if (!$id) { //id should come from URL params
            return Response::json(['error' => 'ID must be given'], 400);
        }

        if (empty($data)) {
            return Response::json(['error' => 'No data provided'], 400);
        }

        // 
        if (isset($data['category'])) {
            $allowedCategories = ['Mobiles', 'Accessories', 'Chargers'];
            if (!in_array($data['category'], $allowedCategories)) {
                return Response::json(['error' => 'Invalid category.'], 422);
            }
        }


        // must be numeric
        if (isset($data['price'])) {
            if (!is_numeric($data['price']) || $data['price'] <= 0) {
                return Response::json(['error' => 'Price must be a positive number.'], 422);
            }
        }

        if (isset($data['stock_quantity'])) {
            if (!is_numeric($data['stock_quantity']) || $data['stock_quantity'] < 0) {
                return Response::json(['error' => 'Stock quantity must be zero or more.'], 422);
            }
        }

        $succes = $this->productModel->updateProduct($id, $data);

        if ($succes > 0) {
            return Response::json(["Data Updated Successfully"]);
        } else {
            return Response::json(["Data Update Failed"], 500);
        }
    }



    public function deleteProduct($id)
    {
        // id must be numeric
        if (!is_numeric($id)) {
            return Response::json(['error' => 'Invalid id.'], 422);
        }

        $this->authorize(['Admin']);

        if (!$id) { // take from url
            return Response::json([
                'error' => 'No id provided'
            ], 400);
        }

        // remove all relational data at a time.

        $delete = $this->productModel->deleteProduct($id);

        if ($delete > 0) {
            return Response::json(['Product is deleted successfully.']);
        } else {
            return Response::json(['Product is deletion Failed.'], 500);
        }
    }
}
