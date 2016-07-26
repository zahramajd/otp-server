<?php require_once '../init.php';

$email = $_POST['email'];
$pwd = $_POST['pwd'];
$key = $_POST['key'];


//$result = $db->users->find([
//    '$and' => [
//        ['email' => $email],
//        ['password' => $pwd],
//    ]
//])->toArray();
//
//if ($result == null) {
//    echo '[{"error":"Invalid"}]';
//} else {
//    echo json_encode($result);
//}
//////

echo $email;
echo $pwd;
echo $key;

// Check for duplicate email
$current = $db->users->findOne(['email' => $_REQUEST['email']]);
if ($current != null) {
    echo '[{"status":"invalid"}]';
} else {
// Insert new user to DB
    $db->users->insertOne([
        'email' => $email,
        'password' => $pwd,
        'key'=>$key,
    ]);
    echo '[{"status":"ok"}]';
}
