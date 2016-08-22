<?php require_once '../init.php';

// Get request input
$email = @$_REQUEST['email'];
$pwd = @$_REQUEST['pwd'];
$key = @$_REQUEST['key'];

//if (!$email || !$pwd ) {
//    echo json_encode(['status' => 'Invalid arguments!']);
//    die();
//}
//$current = User::find_by('email', $email);
//if ($current == null || $current->password != $pwd) {
//    echo json_encode(['status' => 'Invalid email or password!']);
//    die();
//}
//////////////

// Check existence
$current = db()->users->findOne(['email' => $email]);
if ($current != null) {
    $message = 'User exists';
}
// Make a new seed
$seed = sha1("" . (int)(rand(1005, 3234334) * time() / 100));

// Insert new user to DB
db()->users->insertOne([
    'email' => $email,
    'password' => $pwd,
    'seed' => $seed,
]);

// Encrypt seed with key
openssl_public_encrypt($current->seed, $encrypted, $key);
$encrypted = base64_encode($encrypted);
echo $encrypted;