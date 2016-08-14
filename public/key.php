<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$pb = $_POST['pb'];

// Create random seed
$random = mt_rand();
$seed = base64_encode($random);

// Encrypt seed with public key
$pubkey = openssl_get_publickey(base64_decode($pb));
$success = openssl_public_encrypt($seed, $encrypted, $pubkey);


// Check for duplicate email
$current = $db->users->findOne(['email' => $_REQUEST['email']]);
if ($current != null) {
    $data = array('status' => 'invalid');
} else {
// Insert new user to DB
    $db->users->insertOne([
        'email' => $email,
        'password' => $pwd,
        'seed' => $seed,
        'pb' => $pb,
    ]);

    $data = array('status' => 'ok', 'seed' => $success);

}
echo json_encode($data);