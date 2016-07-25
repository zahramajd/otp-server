<?php

// Get project root directory
define('root',dirname(__FILE__));

// Composer
require_once 'vendor/autoload.php';

// Load Config
$conf = json_decode(file_get_contents(root.'/config.json'),true);

// Includes
require_once 'models/User.php';

// Connect to database
global $db;
$m = new MongoDB\Client('mongodb://'.$conf['mongo']);
$db = $m->{$conf['db']};

function db() {
    global $db;
    return $db;
}


// Helper function to make json responses :)
$response=[];
function response_json(){
    global $response;
    echo json_encode($response);
}
