<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$key = $_POST['key'];

$decoded_key =base64_decode($key);


// Check for duplicate email
$current = $db->users->findOne(['email' => $_REQUEST['email']]);
if ($current != null) {
    echo '[{"status":"invalid"}]';
} else {
// Insert new user to DB
    $db->users->insertOne([
        'email' => $email,
        'password' => $pwd,
        'key'=>$decoded_key,
    ]);
    echo '[{"status":"ok"}]';
}
