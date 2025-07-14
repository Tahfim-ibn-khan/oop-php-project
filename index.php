<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Config\Database;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv -> load();


$db = new Database();
$conn = $db -> connect();

if ($conn){
    echo "DB Connected Successfully";
}
else{
    echo "Check Your Connection";
}