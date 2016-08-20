<?php require_once '../init.php';

if (isset($_REQUEST['action'])) {

    $message = "";
    $current = User::find_by('email', $_REQUEST['email']);

    switch ($_REQUEST['action']) {

        // --------------------------------------------------------------------------------------------
        // Login
        // --------------------------------------------------------------------------------------------
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
                    $message = "wrong OTP";
                } else {
                    $message = "logged in :)";
                }
            }
            break;
        
        // --------------------------------------------------------------------------------------------
        // Register
        // --------------------------------------------------------------------------------------------
        case 'register':

            // Check for duplicate email
            $current = db()->users->findOne(['email' => $_REQUEST['email']]);
            if ($current != null) {
                $message = 'User exists';
                break;
            }
            
            // Insert new user to DB
            db()->users->insertOne([
                'email' => $_REQUEST['email'],
                'password' => $_REQUEST['password'],
            ]);

            $message='User created! Now login ... :)';

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

        <img id="profile-img" class="profile-img-card" src="./css/Key.png"/>
        <p id="profile-name" class="profile-name-card"></p>

        <form class="form-signin" method="post">

            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" name="email" class="form-control" placeholder="Email" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <input type="text" name="otp" class="form-control" placeholder="OTP">

            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="action" value="login">
                <span>Login</span>
            </button>

            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="action" value="register">
                <span>Register</span>
            </button>

        </form>
    </div>
</div>


</body>
</html>