<?php require_once '../init.php';

// Get request input
$email = @$_REQUEST['email'];
$pwd = @$_REQUEST['pwd'];
$key = @$_REQUEST['key'];

if (!$email || !$pwd || !$key) {
    echo json_encode(['status' => 'Invalid arguments!']);
    die();
}


//$current = User::find_by('email', $email);
//if ($current == null || $current->password != $pwd) {
//    echo json_encode(['status' => 'Invalid email or password!']);
//    die();
//}

$current = User::find_by('email', $email);
if ($current != null) {
    echo json_encode(['status' => 'already exists']);
    die();
} else {

    $current = User::find_by('email', $email);
    echo 'in else';
    // Insert new user to DB
    db()->users->insertOne([
        'email' => $email,
        'password' => $pwd,
        'seed' => '',
    ]);

    $current = User::find_by('email', $email);
// Make a new seed
    if (!$current->seed) {
        $current->seed = sha1("" . (int)(rand(1005, 3234334) * time() / 100));
    }

// Encrypt seed with key
    openssl_public_encrypt($current->seed, $encrypted, $key);
    $encrypted = base64_encode($encrypted);
    echo $encrypted;

}