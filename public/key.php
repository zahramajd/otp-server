<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$pb = $_POST['pb'];

// Create random seed
$random = mt_rand();
$seed = base64_encode($random);

// Encrypt seed with public key
//$pb="-----BEGIN PUBLIC KEY-----\n" . $pb . "-----END PUBLIC KEY-----\n";
//$pubkey = openssl_get_publickey(base64_decode($pb));
//$pb=base64_decode($pb);
//$success = openssl_public_encrypt($seed, $encrypted, $pb);


//// Create the keypair
//$res = openssl_pkey_new($config);
//
//// Get private key
//openssl_pkey_export($res, $privKey);
//
//// Get public key
//$pubKey = openssl_pkey_get_details($res);
//$pubKey = $pubKey["key"];
//$pubkey = openssl_get_publickey(base64_decode($pubKey));
//
////$PubKey = openssl_pkey_get_public($pb);
//$success = openssl_public_encrypt($seed, $encrypted, $pubkey);

//$pb=base64_decode($pb);
//$ok= openssl_public_encrypt($seed,$encrypted,$pb);


$pubkey = openssl_pkey_get_public($pb);
$ok= openssl_public_encrypt($seed,$encrypted,$pubkey);


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
        'encrypted'=>$encrypted,
    ]);

    $data = array('status' => 'ok', 'seed' => $seed);

}
echo json_encode($data);