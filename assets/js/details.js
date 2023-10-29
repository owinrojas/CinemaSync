// Constants for API and URLs
const api_key = 'd679741f03a2925a326fb72686aa6130';
const imageBaseURL = 'https://image.tmdb.org/t/p/';
const baseURL = 'https://api.themoviedb.org/3';

// Function to make API request
async function apiRequest(endpoint) {
    const response = await fetch(`${baseURL}${endpoint}&api_key=${api_key}`);
    return response.json();
}

// Function to fetch recommended movies based on movie ID
async function fetchRecommendedMovies(movieId) {
    const response = await apiRequest(`/movie/${movieId}/recommendations?language=en-US&page=1`);
    console.log(response);  
    return response.results;
}

async function populateRecommendedMovies(movieId, containerId) {
    const recommendedMovies = await fetchRecommendedMovies(movieId);
    const container = document.getElementById(containerId);
    if (!container) return;

    // Clear the container first
    container.innerHTML = '';

    if (recommendedMovies.length > 0) {
        // Add a title for the Recommended Movies section only if there are any
        const title = document.createElement('h2');
        title.textContent = "Recommended Movies:";
        container.appendChild(title);

        recommendedMovies.forEach(movie => {
            const movieCard = createMovieCard(movie);
            container.appendChild(movieCard);
        });
    }
}

// Function to populate movie details
async function populateMovieDetails(movieId) {
    const movieDetails = await apiRequest(`/movie/${movieId}?language=en-US&append_to_response=casts,videos`);
    const year = (new Date(movieDetails.release_date)).getFullYear();
    document.getElementById("movie-poster").src = `${imageBaseURL}w500${movieDetails.poster_path}`;
    document.getElementById("blurred-bg").style.backgroundImage = `url(${imageBaseURL}w500${movieDetails.poster_path})`;
    document.getElementById("movie-title").textContent = `${movieDetails.title} (${year})`;
    document.getElementById("movie-release-date").textContent = `Release Date: ${movieDetails.release_date}`;
    document.getElementById("movie-genre").textContent = `Genre: ${movieDetails.genres.map(genre => genre.name).join(", ")}`;
    
    // Add Cast information
    document.getElementById("movie-cast").textContent = `Cast: ${movieDetails.casts.cast.slice(0, 10).map(actor => actor.name).join(", ")}`;
    
    // Add Rating
    document.getElementById("movie-rating").textContent = `Rating: ${movieDetails.vote_average}`;
    
    // Add Overview
    document.getElementById("movie-overview").textContent = `Overview: ${movieDetails.overview}`;
    //Add Recommendations
    populateRecommendedMovies(movieId, 'recommendations-container');

    
    // Add Trailer
    const trailerContainer = document.getElementById('trailer-container');
    trailerContainer.innerHTML = "";
    const youtubeTrailer = movieDetails.videos.results.find(video => video.site === 'YouTube');
    if(youtubeTrailer) {
        const iframe = document.createElement('iframe');
        iframe.src = `https://www.youtube.com/embed/${youtubeTrailer.key}`;
        iframe.width = "500";
        iframe.height = "294";
        trailerContainer.appendChild(iframe);
    }
}
// Helper function to create a movie card
// Helper function to create a movie card
function createMovieCard(movie, onCardClick) {
    const movieCard = document.createElement('div');
    movieCard.className = 'movie-card';
    
    const movieTitle = document.createElement('h3');
    movieTitle.textContent = movie.title;
    movieCard.appendChild(movieTitle);
  
    const movieImage = document.createElement('img');
    movieImage.src = `${imageBaseURL}w500${movie.backdrop_path}`;
    movieCard.appendChild(movieImage);

    // Attach the click event to the card if onCardClick is provided
    if (onCardClick) {
        movieCard.addEventListener('click', () => onCardClick(movie));
    }
  
    return movieCard;
}


// Fetch and populate movie details on page load
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('movieId');
    if (movieId) {
        populateMovieDetails(movieId);
    }
});
