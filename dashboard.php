<?php
//TODO: make dashboard for movies and stuff

require 'incl/config.inc.php';
//ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // redirect if not logged in
    exit();
}

$user = $db->getUser($_SESSION['email']); // get user from session

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Dashboard</title>
</head>

<body>
<!--Navigation bar-->
<nav class="navbar bg-body-tertiary">

    <div class="container-fluid">
        <a href="dashboard.php" class="navbar-brand">CinemaSync</a>
        <!--Logout-->
        <div class="d-flex align-items-center">
            <a class="btn btn-dark px-3"
               href="logout.php"
               role="button"
            >Log out</a>
        </div>
        <!--Logout-->
    </div>
</nav>
<!--End Navigation bar-->

<main class="form-signin w-100 m-auto">
        <h1 class="h3 mb-3 fw-normal">Welcome, <?= $user->getData('name') ?></h1>

    <!-- TODO: Make the dashboard to search for movies and stuff -->

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

</html>

