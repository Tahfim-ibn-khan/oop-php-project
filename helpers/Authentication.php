<?php
namespace Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Helpers\Response;

class Authentication{
    
    private $secretKey;

    public function __construct() {
        $this->secretKey = $_ENV['JWT_TOKEN'];
    }

    public function generateToken($userId, $name, $email, $role){
        $payload = [
        'user_id' => $userId,
        'name' => $name,
        'email' => $email,
        'role' => $role,
        'exp' => time() + (60 * 60)
    ];

    $jwt = JWT::encode($payload, $this->secretKey, 'HS256');
    return $jwt;
    }

    public function decodeToken($field = 'user_id'){
        $jwt = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if(!$jwt){
            return false;
        }
        $jwt = str_replace('Bearer ', '',$jwt);
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            return $decoded->$field;

        } catch (Exception $e) {
            return $e;
        }
    }


    // This was used in the products controller but we can achive the same thing with the decodeToken function
    public function verifyRole($role = 'Admin') {
        $jwt = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$jwt) {
            return Response::json(['error' => 'Please Login First'], 401);
        }
    
        $jwt = str_replace('Bearer ', '', $jwt);
    
        $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
    
        if ($decoded->role !== $role) {
            return Response::json(['error' => 'Access Denied:'.$role.' Only'], 403);
        }
    
        return true;
    }
    
}