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

// Automaticly response on end :D
register_shutdown_function('response_json');


// Get user

if(!isset($_REQUEST['token']))
    die('Please provide token');

$user_id=$_REQUEST['token'];

global $user;
$user = User::find_by_id($user_id);

if($user==null)
    die('Invalid token!');

function user() {
    global $user;
    return $user;
}
