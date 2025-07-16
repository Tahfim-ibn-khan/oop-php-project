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

    // You create it once and this is used onece for Each case.
    public function resolve($method, $path) {
        $method = strtolower($method);
        $path = rtrim($path, '/');
    
        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
    
        foreach ($this->routes[$method] as $route => $callback) {
            $routePattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $routePattern = '#^' . rtrim($routePattern, '/') . '$#';
    
            if (preg_match($routePattern, $path, $matches)) {
                array_shift($matches);
                [$controllerInstance, $methodName] = $callback;
                echo call_user_func_array([$controllerInstance, $methodName], $matches);
                return;
            }
        }
    
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }    
    
}
?>