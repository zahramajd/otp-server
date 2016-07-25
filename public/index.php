<?php

require_once '../init.php';


if (isset($_POST['action'])) {
    $message = "";
    $current = User::find_by('email', $_REQUEST['email']);

    switch ($_POST['action']) {
        case 'log-in':
            if ($current == null) {
                $message = "Invalid email";
                break;
            }
            if ($current->password != $_REQUEST['password']) {
                $message = 'Invalid password';
                break;
            } else {
                if (User::current()->generateOTP() != $_REQUEST['OTP']) {
                    $message = "wrong OTP";
                } else
                    $message = "logged in :)";
            }
            break;
        case 'sign-up':

            // Check for duplicate email
            $current = $db->users->findOne(['email' => $_REQUEST['email']]);
            if ($current != null) {
                $message = 'User exists';
                break;
            }
            // Insert new user to DB
            $db->users->insertOne([
                'email' => $_REQUEST['email'],
                'password' => $_REQUEST['password'],
            ]);
            // Get the key and update DB
            $current = User::find_by('email', $_REQUEST['email']);
            $key = User::current()->makeSecretKey();
            $db->users->findOneAndUpdate(['email' => $_REQUEST['email']], ['$set' => ['key' => $key]]);

            $message = 'Your Secret Key is '+$key;
            break;
    }
}
?>

<html>
<head>
    <title>OTP </title>
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

        <!--        <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />-->
        <p id="profile-name" class="profile-name-card"></p>

        <form class="form-signin" method="post">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="email" id="input$Email" name="email" class="form-control" placeholder="Email" required
                   autofocus>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password"
                   required>
            <input type="text" name="OTP" class="form-control" placeholder="OTP">

            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="action" value="log-in">Log
                in
            </button>
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="action" value="sign-up">Sign
                up
            </button>
        </form>
        <!--        <a href="#" class="forgot-password">Forgot your password?</a>-->
    </div>
</div>


</body>
</html>