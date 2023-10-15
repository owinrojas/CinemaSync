<?php

require('incl/config.inc.php'); // require config
$msg = ''; // initialize message

//check if form was submitted

//init register function
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ($_POST['password'] !== $_POST['password1']) {
        $msg = '<div class="alert alert-danger" role="alert">Passwords do not match</div>';
    } else {
        try {
            $db->addUser($name, $email, $password);
            header('location: login.php?r');
        } catch (Exception $e) {
            $msg = '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
        }

    }

}
//end register function
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assets/css/register.css" rel="stylesheet">
    <title>Login</title>


</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

<main class="form-signin w-100 m-auto">
    <form action="" method="post">

        <h1 class="h3 mb-3 fw-normal">Register</h1>
        <p class="mt-2 mb-1">Already registered? <a href="login.php">Sign In</a></p>
        <br>
        <?= $msg ?>
        <div class="form-floating">
            <input type="text" class="form-control" id="nameInput" placeholder="John Doe" name="name"
                   required autofocus>
            <label for="nameInput">Name</label>
        </div>
        <div class="form-floating">
            <input type="email" class="form-control" id="emailInput" placeholder="name@example.com" name="email"
                   required autofocus>
            <label for="emailInput">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="passwordInput" placeholder="Password" name="password"
                   required>
            <label for="passwordInput">Password</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password2" placeholder="Password" name="password1"
                   required>
            <label for="password2">Verify Password</label>
        </div>

        <button class="btn btn-primary w-100 py-2" type="submit">Register</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; <?= date('Y') //Set current year          ?> CinemaSync</p>
    </form>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</html>