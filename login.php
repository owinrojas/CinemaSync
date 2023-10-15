<?php

require('incl/config.inc.php'); // require config
$msg = ''; // initialize error

// check if user already logged in
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit();
}
//end check

if (isset($_GET['r'])) {
    $msg = '<div class="alert alert-success">Success, you may login.</div>';
}

//check if form was submitted
//init login function
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email']; // simplify to vars
    $password = $_POST['password'];

    if ($db->checkUser($email, $password)) { // check user credentials
        $_SESSION['email'] = $email; // set session email to email
        header('Location: dashboard.php'); // redirect to dashboard
    } else {
        $msg = '<div class="alert alert-danger" role="alert">Invalid Login details</div>'; // set error
    }
}
//end login function
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="assets/css/sign-in.css" rel="stylesheet">
    <title>Login</title>


</head>

<body class="d-flex align-items-center py-4 bg-body-tertiary">

<main class="form-signin w-100 m-auto">
    <form action="" method="post">

        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        <p class="mt-2 mb-1">Not Registered? <a href="register.php">Register</a></p>
        <br>
        <?= $msg ?>
        <div class="form-floating">
            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email"
                   required autofocus>
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password"
                   required>
            <label for="floatingPassword">Password</label>
        </div>
        <!--TODO: Remember Me (prob not needed) -->
        <!--<div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
            <label class="form-check-label" for="flexCheckDefault">
                Remember me
            </label>
        </div>-->
        <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-body-secondary">&copy; <?= date('Y') //Set current year       ?> CinemaSync</p>
    </form>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</html>