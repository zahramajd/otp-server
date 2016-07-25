<?php require_once '../init.php';

$email = $_GET['email'];
$pwd = $_GET['pwd'];


$result = $db->users->find([
    '$and' => [
        ['email' => $email],
        ['password' => $pwd],
    ]
])->toArray();

if ($result == null) {
    echo '[{"error":"Invalid"}]';
} else {
    echo json_encode($result);
}