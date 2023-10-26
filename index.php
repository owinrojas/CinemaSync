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
            <h1>FullertonMovies</h1>
            <p>Discover the latest movies.</p>
        </div>
    </section>

    <nav class="navbar">
        <div class="navbar-content">
            <input type="text" id="search-bar" placeholder="Search..." onkeyup="searchMovies(event)">
            <div class="navbar-links">
                <a href="#about-us">About Us</a>

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

    <div id="main-container">
        <div id="genre-list"></div>

        <div id="search-results" class="category-container">
            <div class="movies-container">
            </div>
        </div>

        <div class="category-container" id="popular-movies">
            <h2>Popular Movies</h2>
            <div class="movies-container"></div>
        </div>

        <div class="category-container" id="now-playing">
            <h2>Now Playing</h2>
            <div class="movies-container"></div>
        </div>

        <div class="category-container" id="top-rated">
            <h2>Top Rated</h2>
            <div class="movies-container"></div>
        </div>
    </div>

    <div id="movie-modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">X</button>
            <div class="modal-header">
                <img id="modal-poster" src="">
                <div class="modal-title-details">
                    <h2 id="modal-title"></h2>
                    <p id="modal-genre"></p>
                    <p id="modal-cast"></p>
                </div>
            </div>
            <div class="modal-main-content">
                <p id="modal-overview"></p>
                <div id="modal-trailer-container"></div>
            </div>
        </div>
    </div>

    <script defer src="assets/js/script.js"></script>

</body>

</html>