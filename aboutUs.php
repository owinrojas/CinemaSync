<?php
//TODO: make dashboard for movies and stuff
$loggedIn = false;
require 'incl/config.inc.php';
//ensure user is logged in
if (isset($_SESSION['email'])) {
    $loggedIn = true;
    $user = $db->getUser($_SESSION['email']); // get user from session
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullertonProj</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <section class="banner">
        <div class="banner-content">
            <h1>About Us</h1>
            <p>Popcorn everyone!</p>
        </div>
    </section>



    <section class = "about" id = "about">
        <h1 class ="heading"> <span>The Team</span> </h1>

        <div class = "biography">
            <p>The developers of the website</p>
            <div class = "bio">
                <h3> <span>name : </span> Owin Rojas </h3>
                <h3> <span>name : </span> Ulysses Carbajal </h3>
                <h3> <span>name : </span> Yazid Soulong </h3>
        </div>
    </section>




<!--All navbar stuff below. Leave alone!--->
    <nav class="navbar">
        <div class="navbar-content">

            <?php
            if ($loggedIn) : // If the user is logged in, show the search bar
            ?>
            <input type="text" id="search-bar" placeholder="Search..." onkeyup="searchMovies(event)">
            <?php endif; //End if
            ?>

            <div class="navbar-links">
                <a href="aboutUs.php">About Us</a>

                <?php
                // if user is logged in, show the dashboard button
                if ($loggedIn) :
                ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="dashboard.php">Saved Movies</a>
                    <a href="dashboard.php">Logout</a>

                <?php else : //else show the login / register buttons
                ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; //End if
                ?>

            </div>
        </div>
    </nav>
    <script defer src="assets/js/script.js"></script>
</body>

</html>
