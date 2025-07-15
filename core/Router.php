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

    public function delete($path, $callback){
        $this->routes['delete'][$path] = $callback;
    }



    // You create it once and this is used once for Each case.
    public function resolve($method, $path) {
        $method = strtolower($method);
        $path = rtrim($path, '/');

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];

            if (is_callable($callback)) {
                call_user_func($callback);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Invalid route callback']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Route not found']);
        }
    }

}
?>