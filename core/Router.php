<?php

namespace Core;

class Router{
    private $routes = [];

    // I gotta create new functions for each HTTP methods
    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }


    // You c reate it once and this is used onece for Each case.
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