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


$pubkey = openssl_pkey_get_public("-----BEGIN PUBLIC KEY----- MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0llCeBjy18RylTdBih9G MUSZIC3GzeN0vQ9W8E3nwy2jdeUnH3GBXWpMo3F43V68zM2Qz5epRNmlLSkY/PJU fJIC8Yc1VEokT52q87hH/XJ5eS8heZnjuSlPAGi8oZ3ImVbruzV7XmlD+QsCSxJW 7tBv0dqJ71e1gAAisCXK2m7iyf/ul6rT0Zz0ptYH4IZfwc/hQ9JcMg69uM+3bb4o BFsixMmEQwxKZsXk3YmO/YRjRbay+6+79bSV/frW+lWhknyGSIJp2CJArYcOdbK1 bXx1dRWpbNSExo7dWwuPC0Y7a5AEeoZofieQPPBhXlp1hPgLYGat71pDqBjKLvF5 GwIDAQAB -----END PUBLIC KEY-----");
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