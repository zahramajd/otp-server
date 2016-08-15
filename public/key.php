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


$pubkey = openssl_pkey_get_public("-----BEGIN PUBLIC KEY-----\n2f124830b0cf914ad5c49257fb660d230603518a45f481315e3fa3b8fe6c8a404a3f819610d6f23ee05d1383a13347da06cfc805cf42a51195de8844dc03755394277bf2ed4837cfaef25172057c8bd7d91bc8483a64050e003cb3ebc111b0022debaebd8f10eb5a3aa8c70b3d2d091bcba85da932ac9b20cd3ec119b3fcb7,publicExponent=10001-----END PUBLIC KEY-----");
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
    ]);

    $data = array('status' => 'ok', 'seed' => $encrypted);

}
echo json_encode($data);