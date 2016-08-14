<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$seed = $_POST['seed'];
$pb=$_POST['pb'];

//$decoded_key = base64_decode($key);
//$arr = explode($email, $decoded_key);
//$secret = $arr[0];


// Check for duplicate email
$current = $db->users->findOne(['email' => $_REQUEST['email']]);
if ($current != null) {
    echo '[{"status":"invalid"}]';
} else {
// Insert new user to DB
    $db->users->insertOne([
        'email' => $email,
        'password' => $pwd,
        'seed' => $seed,
        'pb'=>$pb,
    ]);
    echo '[{"status":"ok"}]';
}