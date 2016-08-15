<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$pb = $_POST['pb'];

// Create random seed
$random = mt_rand();
$seed = base64_encode($random);

// Encrypt seed with public key
//$pb="-----BEGIN PUBLIC KEY-----\n" . $pb . "-----END PUBLIC KEY-----\n";
//$pubkey = openssl_get_publickey(base64_encode($pb));
//$success = openssl_public_encrypt($seed, $encrypted, $pb);

$pb=base64_decode($pb);
$ok= openssl_public_encrypt($seed,$encrypted,$pb);


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

    $data = array('status' => 'ok', 'seed' => $encrypted);

}
echo json_encode($data);