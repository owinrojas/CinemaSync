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
            <h1>CinemaSync</h1>
            <p>Discover the latest movies</p>
        </div>
    </section>

    <nav class="navbar">
        <div class="navbar-content">

            <?php
            if ($loggedIn) : // If the user is logged in, show the search bar
            ?>
            <input type="text" id="search-bar" placeholder="Search..." onkeyup="searchMovies(event)">
            <?php endif; //End if
            ?>

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

        <div class="category-container" id="now-playing">
            <h2>Now Playing</h2>
            <div class="movies-container"></div>
        </div>

        <div id="genre-list"></div>

        <?php
        if($loggedIn): // If the user is logged in, show the Popular and Top Rated movies
        ?>

        
        
        <div id="search-results" class="category-container">
            <div class="movies-container">
            </div>
        </div>

        <div class="category-container" id="popular-movies">
            <h2>Popular Movies</h2>
            <div class="movies-container"></div>
        </div>
        
        <div class="category-container" id="top-rated">
            <h2>Top Rated</h2>
            <div class="movies-container"></div>
        </div>
        <?php endif; //End if
        ?>
    </div>

    <div id="movie-modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">Close X</button>
            <div class="modal-header">
                <div class="modal-title-details">
                    <h2 id="modal-title"></h2>  <!--Title of the movie-->
                    <p id="modal-genre"></p>    <!--Genre of the movie-->
                    <p id="modal-cast"></p>     <!--Cast of the movie-->
                    <p id="modal-overview"></p> 
                </div>
            </div>
            <div class="modal-main-content">
                <img id="modal-poster" src="">
                <div id="modal-trailer-container"></div>
            </div>
        </div>
    </div>

    <script defer src="assets/js/script.js"></script>

</body>

</html>