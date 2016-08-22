<?php require_once '../init.php';

// Get request input
$email = @$_REQUEST['email'];
$pwd = @$_REQUEST['password'];
$key = @$_REQUEST['key'];

if (!$email || !$pwd ) {
    echo json_encode(['status' => 'Invalid arguments!']);
    die();
}


//$current = User::find_by('email', $email);
//if ($current == null || $current->password != $pwd) {
//    echo json_encode(['status' => 'Invalid email or password!']);
//    die();
//}

//////////////
// Sign up
$current = db()->users->findOne(['email' => $_REQUEST['email']]);
if ($current != null) {
    $message = 'User exists';
}

// Insert new user to DB
db()->users->insertOne([
    'email' => $_REQUEST['email'],
    'password' => $_REQUEST['password'],
]);
$current=db()->users->findOne(['email' => $_REQUEST['email']]);
//////////////
// Make a new seed
if (!$current->seed) {
    $current->seed = sha1("" . (int)(rand(1005, 3234334) * time() / 100));
}

// Encrypt seed with key
openssl_public_encrypt($current->seed, $encrypted, $key);
$encrypted = base64_encode($encrypted);
echo $encrypted;