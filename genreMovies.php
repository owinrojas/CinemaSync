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

<script type="text/javascript">
    var isLoggedIn = <?php echo $loggedIn ? 'true' : 'false'; ?>;
</script>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullertonProj</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://kit.fontawesome.com/7183df4a39.js" crossorigin="anonymous"></script>

</head>

<body>

    <nav class="navbar">
        <div class="navbar-content">
            <div class="navbar-links">
                <a href="index.php">Home</a>
                <a href="#about-us">About Us</a>
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

    <div id="main-container"> <!--This showcases the movies and whatnot-->
        <div id="genre-list"></div> <!--Genre list on the left-->

        <div class="category-container" id="genre-movies-container">
            <h2 id="genre-title">Movies by Genre</h2>
            <div id="movie-grid" class="movies-container"></div>
        </div>
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

    <section class = "copyright" id = "copyright">
        <div class = "copyright-content">
        <a href = "https://www.youtube.com/watch?v=dQw4w9WgXcQ">© CinemaSync 2023</a>
        </div>
    </section>

    </main>
    <script defer src="assets/js/script.js"></script>
</body>
</html>