 'use strict';

const api_key = 'd679741f03a2925a326fb72686aa6130';
const imageBaseURL = 'https://image.tmdb.org/t/p/';
const baseURL = 'https://api.themoviedb.org/3';

//==API Request functions==

// Function to make an API request
async function apiRequest(endpoint) {
    const response = await fetch(`${baseURL}${endpoint}&api_key=${api_key}`);
    return response.json();
}

// Fetch genres from API
async function fetchGenresFromAPI() {
    const response = await apiRequest('/genre/movie/list?language=en-US');
    return response.genres;
}


// Fetch movies by category (popular, now playing, etc.)
async function fetchMoviesByCategory(category) {
    const response = await apiRequest(`/movie/${category}?language=en-US&page=1`);
    return response.results;
}

// Function to fetch recommended movies based on movie ID
async function fetchRecommendedMovies(movieId) {
    const response = await apiRequest(`/movie/${movieId}/recommendations?language=en-US&page=1`);
    return response.results;
}

// Fetch movies by a specific genre
async function fetchMoviesByGenre(genreId) {
    const response = await apiRequest(`/discover/movie?with_genres=${genreId}&language=en-US&page=1`);
    return response.results;
}


//==Genre Handling Functions==

// Populate the genres on the page
function fetchGenres() {
    const genreList = document.getElementById('genre-list');
    if (!genreList) return;

    fetchGenresFromAPI().then(genres => {
        genres.forEach(genre => {
            const genreItem = document.createElement('a');
            genreItem.classList.add('genre-list-item');
            genreItem.textContent = genre.name;
            genreItem.href = `genreMovies.php?genreId=${genre.id}&genreName=${genre.name}`;
            genreItem.setAttribute("data-genre-id", genre.id);
            genreItem.setAttribute("data-genre-name", genre.name);
            genreList.appendChild(genreItem);
        });
    });
}


// Event listener for clicking on genre items
document.addEventListener('click', function (event) {
    if (event.target.matches('.genre-list-item')) {
        const genreId = event.target.getAttribute("data-genre-id");
        const genreName = event.target.getAttribute("data-genre-name");
        window.location.href = `genreMovies.php?genreId=${genreId}&genreName=${genreName}`;
    }
});

//==Movie Card Functions==

// Function to create a movie card
function createMovieCard(movie) {
    // Create the card and its components
    const movieCard = document.createElement('div');
    movieCard.classList.add('movie-card');

    const movieImage = document.createElement('img');
    movieImage.src = `${imageBaseURL}w500${movie.poster_path}`;

    const movieTitleYear = document.createElement('div');
    movieTitleYear.classList.add('title-year');
    const year = (new Date(movie.release_date)).getFullYear();
    movieTitleYear.textContent = `${movie.title} (${year})`;

    const movieRating = document.createElement('div');
    movieRating.classList.add('rating');
    movieRating.textContent = movie.vote_average;

    // Declare saveButton variable to make it accessible outside the if block
    let saveButton;

    // Create and attach the Save Movie Button only if user is logged in
    console.log("Is user logged in?", isLoggedIn);
    if (isLoggedIn) {
        saveButton = document.createElement("button");
        const iconElement = document.createElement("i");
        iconElement.className = "fa-regular fa-bookmark";
        saveButton.appendChild(iconElement);

        saveButton.addEventListener("click", function(event) {
            console.log(`Movie ${movie.id} saved!`);
        
            fetch(`http://movie.test/api.php?a=addmovie&movie_id=${movie.id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data); // Log the response data from the server
                    iconElement.className = "fa-solid fa-bookmark";
                })
                .catch(error => {
                    console.error("There was a problem with the fetch operation:", error);
                });
        
            event.stopPropagation(); // Prevent the event from bubbling up to parent elements
        });
        

        // Append the saveButton to the movieCard
        movieCard.append(saveButton);
    }

    // Append all other elements to the card
    movieCard.append(movieImage, movieTitleYear, movieRating);
                //?maybe somethin with php
 // Event to open modal on card click or navigate to details page
 movieCard.addEventListener('click', (event) => {
    console.log("Is user logged in?", isLoggedIn);
    if (isLoggedIn) {
        populateRecommendedMovies(movie.id, 'recommendations-container');  // Changed movieId to movie.id
        window.location.href = `details.html?movieId=${movie.id}`;
    } else {
        openMovieModal(movie);
    }
});

    return movieCard;
}


// Function to populate movies by category
async function populateMoviesByCategory(category, containerId) {
    const movies = await fetchMoviesByCategory(category);
    const container = document.getElementById(containerId);
    if (!container) return;

    movies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        container.appendChild(movieCard);
    });
}



// Populate movies by a specific genre
async function populateMoviesByGenre(genreId, containerId) {
    const movies = await fetchMoviesByGenre(genreId);
    const container = document.getElementById(containerId);
    if (!container) return;

    movies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        container.appendChild(movieCard);
    });
}




async function populateRecommendedMovies(movieId, containerId) {
    const recommendedMovies = await fetchRecommendedMovies(movieId);
    const container = document.getElementById(containerId);
    if (!container) return;

    recommendedMovies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        container.appendChild(movieCard);
    });
}

//==Search Functions==




function searchMovies(event) {
    const searchField = document.getElementById("search-bar");
    const searchResultsContainer = document.getElementById("search-results");
    const movieGrid = searchResultsContainer.querySelector(".movies-container");
    const titleElement = document.querySelector('#Search-title');


    if (!movieGrid) {
        console.error('Error: Unable to locate the movieGrid element.');
        return;
    }

    const popularContainer = document.getElementById("popular-movies");
    const nowPlayingContainer = document.getElementById("now-playing");
    const topRatedContainer = document.getElementById("top-rated");
    const comingSoonContainer = document.getElementById("coming-soon");

    const query = searchField.value.trim();

    if (!query) {
        titleElement.style.display = "none";  
        searchResultsContainer.style.display = "none";
        popularContainer.style.display = "block";
        nowPlayingContainer.style.display = "block";
        topRatedContainer.style.display = "block";
        comingSoonContainer.style.display = "block";
        return;
    }

    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(async function () {
        const response = await apiRequest(`/search/movie?query=${query}&language=en-US&page=1`);
        movieGrid.innerHTML = "";
        popularContainer.style.display = "none";
        nowPlayingContainer.style.display = "none";
        topRatedContainer.style.display = "none";
        comingSoonContainer.style.display = "none";
        titleElement.style.display = "block"; 
        searchResultsContainer.style.display = "block";

        if (titleElement) {
            titleElement.textContent = query ? `Search Results for "${query}"` : "Showing All Results";
        }
        response.results.forEach(movie => {
            const movieCard = createMovieCard(movie);
            movieGrid.appendChild(movieCard);
        });
    }, 500);
}

//==Modal Functions==


// Function to open the movie modal
async function openMovieModal(movie) {
    const modal = document.getElementById('movie-modal');
    const modalPoster = document.getElementById('modal-poster');
    const modalTitle = document.getElementById('modal-title');
    const modalOverview = document.getElementById('modal-overview');
    const modalGenre = document.getElementById('modal-genre');
    const modalCast = document.getElementById('modal-cast');
    const trailerContainer = document.getElementById('modal-trailer-container');
    const modalReleaseDate = document.getElementById('modal-release-date');
    


    // Clearing previous trailer if any
    trailerContainer.innerHTML = '';

    const movieDetails = await apiRequest(`/movie/${movie.id}?append_to_response=releases,casts,videos`);
    const {
        title,
        release_date,
        runtime,
        vote_average,
        releases: { countries: [{ certification } = { certification: "N/A" }] },
        genres,
        overview,
        casts: { cast, crew },
        videos: { results: videos }
    } = movieDetails;

    modalPoster.src = `${imageBaseURL}w500${movie.poster_path}`;
    modalTitle.textContent = title;
    modalGenre.textContent = `Genre: ${genres.map(genre => genre.name).join(", ")}`;
    modalCast.textContent = `Actors: ${cast.slice(0, 5).map(actor => actor.name).join(", ")}`;
    modalOverview.textContent = `Overview: ${overview}`;
    modalReleaseDate.textContent = `Release Date: ${movieDetails.release_date}`;


    // Adding Trailer
    for (const { key, name } of filterVideos(videos)) {
        const videoCard = document.createElement("div");
        videoCard.classList.add("video-card");
        videoCard.innerHTML = `
            <iframe width="500" height="294" src="https://www.youtube.com/embed/${key}?&theme=dark&color=white&rel=0"
                frameborder="0" allowfullscreen="1" title="${name}" class="img-cover" loading="lazy"></iframe>
        `;
        trailerContainer.appendChild(videoCard);
    }

    // Display the modal
    modal.style.display = 'flex';
}

function filterVideos(videos) {
    return videos.filter(video => video.site === 'YouTube' && video.type === 'Trailer').slice(0, 1);
}



// Function to close the movie modal
function closeModal() {
    const modal = document.getElementById('movie-modal');
    modal.style.display = 'none';
}

//==Initializations==

// Initial fetch and populate functions

fetchGenres();
populateMoviesByCategory('popular', 'popular-movies');
populateMoviesByCategory('now_playing', 'now-playing');
populateMoviesByCategory('top_rated', 'top-rated');
populateMoviesByCategory('upcoming', 'coming-soon');


// Check if a specific genre is selected
const urlParams = new URLSearchParams(window.location.search);
const genreId = urlParams.get('genreId');
const genreName = urlParams.get('genreName');
if (genreId) {
    populateMoviesByGenre(genreId, 'movie-grid');
    if (genreName) {
        const titleElement = document.querySelector('#genre-title');
        if (titleElement) {
            titleElement.textContent = `${genreName} Movies`;
        }
    }
}








