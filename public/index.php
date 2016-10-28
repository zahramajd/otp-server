<?php require_once '../init.php';

if (isset($_REQUEST['action'])) {

    $message = "";
    $current = User::find_by('email', $_REQUEST['email']);

    switch ($_REQUEST['action']) {

        case 'login':

            if ($current == null) {
                $message = "Email not found!";
                break;
            }
            if ($current->password != $_REQUEST['password']) {
                $message = 'Invalid password';
                break;
            } else {
                if ($current->otp()->now() != $_REQUEST['otp']) {
                    $message ='wrong OTP ';
                } else {

                    // Prevent using this key again by another user

                    // Check if current otp was not already used
                    if($current->last_otp == $_REQUEST['otp']) {
                        $message = "Code already used!";
                    } else {
                        // Update last_otp using magic field
                        // (This will immediately save current state)
                        $current->last_otp = $_REQUEST['otp'];
                        $message = "logged in :)";
                    }

                }
            }
            break;
    }
}
?>

<html>
<head>
    <title>TOTP Server</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>


<div class="container">
    <div class="card card-container">
        <?php if (isset($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <img id="profile-img" class="profile-img-card" src="./css/key.png"/>
        <p id="profile-name" class="profile-name-card"></p>

        <form class="form-signin" method="post">

            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" name="email" class="form-control" placeholder="Username" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <input type="text" name="otp" class="form-control" placeholder="OTP">

            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="action" value="login">
                <span>Login</span>
            </button>
        </form>
    </div>
</div>


</body>
</html>