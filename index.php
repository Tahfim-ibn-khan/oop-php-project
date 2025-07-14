<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Config\Database;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv -> load();

require_once __DIR__ . '/routes/api.php';