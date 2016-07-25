<?php require_once '../init.php';

$email = $_GET['email'];
$pwd = $_GET['pwd'];


//$user=User::find_by('email', $email);
//$result=$user->toArray();

//if($user->toArray()->password != $pwd)
//    $result="wrong password";
//else

$result = $db->users->find([
    '$and' => [
        ['email' => $email],
        ['password' => $pwd],
    ]
])->toArray();

if ($result == null)
    $result = 'wrong input';

echo json_encode($result);