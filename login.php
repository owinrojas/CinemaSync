<?php

require('incl/config.inc.php');

if (isset($_POST['email']) && isset($_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($db->checkUser($email, $password)) {
        echo "Logged in";
    } else {
        echo "Invalid credentials";
    }
    $user = $db->getUser($email);
    $_SESSION['email'] = $email;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form action="" method="post">
        <input type="text" name="email" placeholder="email">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Login">
    </form>

    <?= $_SESSION['email'] ?>
</body>

</html>