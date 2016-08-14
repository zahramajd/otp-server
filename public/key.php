<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$pb = $_POST['pb'];

// Create random seed
$random = mt_rand();
$seed = base64_encode($random);


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
        'pb' => $pb,
    ]);

    $data = array('status' => 'ok', 'seed' => $seed);
    echo json_encode($data);
    // echo '{{"status":"ok"},{"seed":'."$seed".'}}';
}