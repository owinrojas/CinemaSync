<?php
$loggedIn = false;
require 'incl/config.inc.php';
//ensure user is logged in
if (isset($_SESSION['email'])) {
    $loggedIn = true;
    $user = $db->getUser($_SESSION['email']); // get user from session
}
?>

<script type="text/javascript">
    var isLoggedIn = <?php echo $loggedIn ? 'true' : 'false'; ?>;
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CinemaSync</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://kit.fontawesome.com/7183df4a39.js" crossorigin="anonymous"></script>
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
                <a href="index.php">Home</a>
                <a href="aboutUs.php">About Us</a>
                
                <?php
                // if user is logged in, show the dashboard button
                if ($loggedIn) :
                ?>
                    <a href="dashboard.php">Dashboard</a>
                    <a href="logout.php">Logout</a>

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

        <div id="genre-list"></div> <!--Conveniently move this out of the if statement if wanting to test without being logged in-->

        <?php
        if ($loggedIn) : // If the user is logged in, show whatever is below this
        ?>

            <div id="search-results" class="category-container"> <!--Conveniently move this out of the if statement if wanting to test without being logged in-->
                <div class="movies-container">
                </div>
            </div>

            <div class="category-container" id="coming-soon">
                <h2>Upcoming</h2>
                <div class="movies-container"></div>
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
            <button class="close-btn" onclick="closeModal()"><i class="fa-solid fa-square-xmark"></i>
            </button>

            <div class="modal-header">
                <div id="modal-trailer-container"></div>
            </div>

            <div class="modal-title-details">
                <img id="modal-poster" src="">

                <div class="text-details">
                    <h2 id="modal-title"></h2>
                    <p id="modal-genre"></p>
                    <div id="modal-release-date"></div>
                    <p id="modal-cast"></p>
                    <p id="modal-overview"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>


</body>

</html>