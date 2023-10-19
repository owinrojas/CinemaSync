'use strict';

const api_key = 'd679741f03a2925a326fb72686aa6130';
const imageBaseURL = 'https://image.tmdb.org/t/p/';
const baseURL = 'https://api.themoviedb.org/3';

async function apiRequest(endpoint) {
    const response = await fetch(`${baseURL}${endpoint}&api_key=${api_key}`);
    return response.json();
}

async function fetchGenresFromAPI() {
    const response = await apiRequest('/genre/movie/list?language=en-US');
    return response.genres;
}

function fetchGenres() {
    const genreList = document.getElementById('genre-list');
    if (!genreList) return;

    fetchGenresFromAPI().then(genres => {
        genres.forEach(genre => {
            const genreItem = document.createElement('a');
            genreItem.classList.add('genre-list-item');
            genreItem.textContent = genre.name;
            genreItem.href = `genreMovies.html?genreId=${genre.id}&genreName=${genre.name}`;
            genreItem.setAttribute("data-genre-id", genre.id);
            genreItem.setAttribute("data-genre-name", genre.name);
            genreList.appendChild(genreItem);
        });
    });
}

async function fetchMoviesByCategory(category) {
    const response = await apiRequest(`/movie/${category}?language=en-US&page=1`);
    return response.results;
}

document.addEventListener('click', function(event) {
    if (event.target.matches('.genre-list-item')) {
        const genreId = event.target.getAttribute("data-genre-id");
        const genreName = event.target.getAttribute("data-genre-name");
        window.location.href = `genreMovies.html?genreId=${genreId}&genreName=${genreName}`;
    }
});

function openMovieModal(movie) {
    const modal = document.getElementById('movie-modal');
    const modalPoster = document.getElementById('modal-poster');
    const modalTitle = document.getElementById('modal-title');
    const modalOverview = document.getElementById('modal-overview');

    modalPoster.src = `${imageBaseURL}w500${movie.poster_path}`;
    modalTitle.textContent = movie.title;
    modalOverview.textContent = movie.overview;

    modal.style.display = 'flex';
}

function closeModal() {
    const modal = document.getElementById('movie-modal');
    modal.style.display = 'none';
}

function createMovieCard(movie) {
    const movieCard = document.createElement('div');
    movieCard.classList.add('movie-card');
    
    const movieImage = document.createElement('img');
    movieImage.src = `${imageBaseURL}w500${movie.poster_path}`;
    
    const movieTitle = document.createElement('div');
    movieTitle.classList.add('title');
    movieTitle.textContent = movie.title;
    
    const movieYear = document.createElement('div');
    movieYear.classList.add('year');
    movieYear.textContent = (new Date(movie.release_date)).getFullYear();
    
    const movieRating = document.createElement('div');
    movieRating.classList.add('rating');
    movieRating.textContent = movie.vote_average;
    
    movieCard.append(movieImage, movieTitle, movieYear, movieRating);
    
    movieCard.addEventListener('click', () => {
        openMovieModal(movie);
    });
    
    return movieCard;
}

async function populateMoviesByCategory(category, containerId) {
    const movies = await fetchMoviesByCategory(category);
    const container = document.getElementById(containerId);
    if (!container) return;

    movies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        container.appendChild(movieCard);
    });
}

async function fetchMoviesByGenre(genreId) {
    const response = await apiRequest(`/discover/movie?with_genres=${genreId}&language=en-US&page=1`);
    return response.results;
}

async function populateMoviesByGenre(genreId, containerId) {
    const movies = await fetchMoviesByGenre(genreId);
    const container = document.getElementById(containerId);
    if (!container) return;

    movies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        container.appendChild(movieCard);
    });
}

function searchMovies(event) {
    const searchField = document.getElementById("search-bar");
    const searchResultsContainer = document.getElementById("search-results");
    const movieGrid = searchResultsContainer.querySelector(".movies-container");
    
    if (!movieGrid) {
        console.error('Error: Unable to locate the movieGrid element.');
        return;
    }

    console.log("movieGrid:", movieGrid);

    const popularContainer = document.getElementById("popular-movies");
    const nowPlayingContainer = document.getElementById("now-playing");
    const topRatedContainer = document.getElementById("top-rated");

    if (!searchField.value.trim()) {
        searchResultsContainer.style.display = "none";

        popularContainer.style.display = "block";
        nowPlayingContainer.style.display = "block";
        topRatedContainer.style.display = "block";
        
        return;
    }

    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(async function() {
        const query = searchField.value;
        const response = await apiRequest(`/search/movie?query=${query}&language=en-US&page=1`);
        
        movieGrid.innerHTML = "";

        popularContainer.style.display = "none";
        nowPlayingContainer.style.display = "none";
        topRatedContainer.style.display = "none";
        
        searchResultsContainer.style.display = "block";
        
        const titleElement = document.querySelector('#genre-title');
        if (titleElement) {
            titleElement.textContent = `Search Results for "${query}"`;
        }

        const limitedResults = response.results.slice(0, 10);

        limitedResults.forEach(movie => {
            const movieCard = createMovieCard(movie);
            movieGrid.appendChild(movieCard);
        });

    }, 500);
}

fetchGenres();
populateMoviesByCategory('popular', 'popular-movies');
populateMoviesByCategory('now_playing', 'now-playing');
populateMoviesByCategory('top_rated', 'top-rated');

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


async function openMovieModal(movie) {
    const modal = document.getElementById('movie-modal');
    const modalPoster = document.getElementById('modal-poster');
    const modalTitle = document.getElementById('modal-title');
    const modalOverview = document.getElementById('modal-overview');
    const modalGenre = document.getElementById('modal-genre');
    const modalCast = document.getElementById('modal-cast');
    const trailerContainer = document.getElementById('modal-trailer-container');
    
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
    modalOverview.textContent = overview;
    modalGenre.textContent = genres.map(genre => genre.name).join(", ");
    modalCast.textContent = cast.slice(0, 5).map(actor => actor.name).join(", ");  

    for (const { key, name } of filterVideos(videos)) {
        const videoCard = document.createElement("div");
        videoCard.classList.add("video-card");

        videoCard.innerHTML = `
            <iframe width="500" height="294" src="https://www.youtube.com/embed/${key}?&theme=dark&color=white&rel=0"
                frameborder="0" allowfullscreen="1" title="${name}" class="img-cover" loading="lazy"></iframe>
        `;
        trailerContainer.appendChild(videoCard);
    }

    modal.style.display = 'flex';
}

function filterVideos(videos) {
    return videos.filter(video => video.site === 'YouTube' && video.type === 'Trailer').slice(0, 1);
}
