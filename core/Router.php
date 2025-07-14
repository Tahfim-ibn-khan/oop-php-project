<?php

namespace Core;

class Router{
    private $routes = [];

    // I gotta create new functions for each HTTP methods
    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function patch($path, $callback){
        $this->routes['patch'][$path] = $callback;
    }


    // You create it once and this is used onece for Each case.
    public function resolve($method, $path) {
    $method = strtolower($method);
    $path = rtrim($path, '/');     

    if (isset($this->routes[$method][$path])) {
        [$class, $methodName] = $this->routes[$method][$path];
        echo (new $class)->$methodName();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}

}
?>