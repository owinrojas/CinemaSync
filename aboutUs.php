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
    <section class="purposeBanner">
        <div class="purposeBanner-content">
            <h1>Purpose</h1>
            <p>Why use Cinemasync?</p>
        </div>
    </section>

    <section class = "purpose" id = "purpose">
        <div class = "purpose-content">
            <h3>Welcome to Cinemasync, your go-to destination for a new era of entertainment that prioritizes your privacy. At Cinemasync, we believe that you shouldn't have to sacrifice your personal data to enjoy the latest movies and TV shows. Our platform is built on a foundation of trust, security, and respect for your privacy.</h3>
        </div>
    </section>

    <section class="theTeamBanner">
        <div class="theTeamBanner-content">
            <h1>The Team</h1>
            <p>The great minds of this website!</p>
        </div>
    </section>

    <section class = "about" id = "about">
        <div class = "biography">
            <div class = "bio">
                <h3> <span>Owin Rojas </span></h3>
                <h3> <span>Ulysses Carbajal</span></h3>
                <h3> <span>Yazid Soulong</span></h3>
        </div>
    </section>

    <section class = "sourcesUsed" id = "sourcesUsed">
        <div class = "sourcesUsed-content">
            The website is powered by:<a href = "https://www.themoviedb.org/"> themoviedb</a>
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
                <a href="index.php">Home</a>
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
