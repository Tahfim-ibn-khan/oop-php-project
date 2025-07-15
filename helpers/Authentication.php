<?php
namespace Helpers;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

    public function decodeToken($field){
        $jwt = $_SERVER['HTTP_AUTHORIZATION'];
        $jwt = str_replace('Bearer ', '',$jwt);

        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            return $decoded->$field;
        } catch (Exception $e) {
            return $e;
        }
    }
}