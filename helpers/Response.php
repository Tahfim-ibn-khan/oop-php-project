<?php 

namespace Helpers;

use Core\Router;


// This is basically created tio create to give proper structure to the Responses

// json_encode is basically converts fron assosiative array to JSON string.
// And json_decode does vice versa.
class Response{
    public static function json($data, $code=200){
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
