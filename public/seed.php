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

// Sign up
$current = User::find_By('email', $email);
if ($current != null) {
    echo json_encode(['status' => 'Username already exists']);
    die();
}

// Make seed
$seed = sha1("" . (int)(rand(1005, 3234334) * time() / 100));

// Insert new user to DB
db()->users->insertOne([
    'email' => $email,
    'password' => $pwd,
    'seed' => $seed,
]);

$current=User::find_by('email',$email);
// Encrypt seed with key
openssl_public_encrypt($current->seed, $encrypted, $key);
$encrypted = base64_encode($encrypted);
echo $encrypted;