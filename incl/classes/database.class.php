<?php

/**
 *
 * Database Class
 *
 * @version 1.0
 *
 */

class Database
{

    public PDO $db;

    /**
     * @param $DB_NAME
     * @param $DB_HOST
     * @param $DB_USER
     * @param $DB_PASSWORD
     */
    function __construct($DB_NAME, $DB_HOST, $DB_USER, $DB_PASSWORD)
    {
        $this->db = new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
    }

    /**
     * function returns a User object if email exists
     * @param $email
     * @return User|null
     */
    function getUser($email): User|null
    {
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->bindParam('email', $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }
        foreach ($stmt->fetchAll() as $row) {
            $id = $row['id'];
            return new User($id, $this);
        }
        return null;
    }

    /**
     * function returns a User object by user id
     * @param $email
     * @return User|null
     */
    function getUserById($uid): User|null
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam('id', $uid);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            return null;
        }
        foreach ($stmt->fetchAll() as $row) {
            $id = $row['id'];
            return new User($id, $this);
        }
        return null;
    }

    /**
     * Function to validate user login
     * @param $email
     * @param $password
     * @return bool
     */
    function checkUser($email, $password): bool
    {
        $hash = '';

        $stmt = $this->db->prepare('SELECT id, password FROM users WHERE email = :email');
        $stmt->bindParam('email', $email);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return false;
        }
        foreach ($stmt->fetchAll() as $row) {
            $hash = $row['password'];
        }
        return password_verify($password, $hash);
    }

    /**
     * Function to add user to database
     * @param $email
     * @param $password
     * @return bool
     * @throws Exception
     */
    function addUser($name, $email, $password): bool
    {
        $date = date('Y-m-d H:i:s');
        if ($this->getUser($email) !== null) {
            throw new Exception('User already exists');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid E-Mail');
        }
        if (strlen($password) < 8) {
            throw new Exception('Password must be 8 characters or longer');
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password, updated_at, created_at) VALUES (:name, :email, :password, :updated_at, :created_at)');
        $stmt->bindParam('name', $name);
        $stmt->bindParam('email', $email);
        $stmt->bindParam('password', $password_hash);
        $stmt->bindParam('updated_at', $date);
        $stmt->bindParam('created_at', $date);
        return $stmt->execute();
    }

    /**
     * returns # of users in the database (to be used later)
     * @return int
     */
    function getUserCount(): int
    {
        $stmt = $this->db->prepare('SELECT null FROM users');
        $stmt->execute();
        return $stmt->rowCount();
    }


    /**
     * returns all the users in an html table (to be used later)
     * @return string
     */
    function returnUsers(): string
    {
        $htmlCode = '';
        $stmt = $this->db->prepare('SELECT * FROM users');
        $stmt->execute();
        foreach ($stmt->fetchAll() as $row) {
            $user = new User($row['id'], $this);
            $rank = 'User';


            $htmlCode .= '
      <tr>
       <th scope=\'row\'>' . $user->id . '</th>
       <td>' . $user->getData('email') . '</td>
       <td>' . $rank . '</td>
      </tr>';
        }

        return $htmlCode;
    }

    /**
     * The function `addMovie` inserts a movie into the database with the specified user ID and movie
     * ID.
     * 
     * @param user_id The user_id parameter is the ID of the user who wants to add a movie.
     * @param movieid The movieid parameter is the ID of the movie that you want to add to the
     * database.
     */
    function addMovie($user_id, $movie_id): bool
    {
        $date = date('Y-m-d H:i:s');
        //get the release date of the movie.
        $release_date = $this->getMovieRelease($movie_id);
        //date conversion for database query
        $release_date = date('Y-m-d H:i:s', strtotime($release_date));

        //check if movie has been released
   /*      if (strtotime($release_date) < time()) {
            throw new Exception('Movie has already been released');
        } */
        // check if already in database
        $precheck = $this->db->prepare('SELECT * FROM movies WHERE user_id = :user_id AND movie_id = :movie_id');
        $precheck->bindParam('user_id', $user_id);
        $precheck->bindParam('movie_id', $movie_id);
        $precheck->execute();

        if ($precheck->rowCount() > 0) {
            return false;
        }
        $movie = $this->getMovieName($movie_id);
        //insert into database
        $stmt = $this->db->prepare('INSERT INTO movies (user_id, movie_id, movie_name, release_date, updated_at, created_at) VALUES (:user_id, :movie_id, :movie_name, :release_date, :updated_at, :created_at)');
        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('movie_id', $movie_id);
        $stmt->bindParam('movie_name', $movie);
        $stmt->bindParam('release_date', $release_date);
        $stmt->bindParam('updated_at', $date);
        $stmt->bindParam('created_at', $date);

        return $stmt->execute();
    }

    /**
     * The function checks if a movie exists in the database for a given user.
     * 
     * @param user_id The user_id parameter is the ID of the user for whom we want to check if they
     * have a specific movie.
     * @param movie_id The movie_id parameter is the unique identifier for a movie in the database. It
     * is used to check if a specific movie exists for a given user.
     * 
     * @return bool a boolean value. It will return true if there is a row in the "movies" table that
     * matches the given user_id and movie_id, and false otherwise.
     */
    function checkMovie($user_id, $movie_id): bool

    {
        $stmt = $this->db->prepare('SELECT * FROM movies WHERE user_id = :user_id AND movie_id = :movie_id');
        $stmt->bindParam('user_id', $user_id);
        $stmt->bindParam('movie_id', $movie_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * The function removes a movie from the database based on its ID.
     * 
     * @param id The "id" parameter is the unique identifier of the movie that needs to be removed from
     * the database.
     */
    function removeMovie($id): void
    {
        $stmt = $this->db->prepare('DELETE FROM movies WHERE id = :id');
        $stmt->bindParam('id', $id);
        $stmt->execute();
    }
    // function get all saved movies from database
    function getMovies(): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM movies');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * The function GetMoviesByUser retrieves all movies associated with a specific user ID from a
     * database.
     * 
     * @param id The parameter "id" is the user ID that is used to filter the movies. The function
     * retrieves all movies from the database that are associated with the specified user ID.
     * 
     * @return an array of movies that belong to a specific user, identified by their user ID.
     */
    function GetMoviesByUser($id): mixed
    {
        $stmt = $this->db->prepare('SELECT * FROM movies WHERE user_id = :id');
        $stmt->bindParam('id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    function GenTable($id): string
    {
        $data = $this->GetMoviesByUser($id);
        $htmlCode = '';
        foreach ($data as $row) {
            $release_date = date('Y-m-d', strtotime($row['release_date']));
            $htmlCode .= '
        <tr>
            <th>' . $row['movie_name'] . '</th>
            <td>' . $release_date . '</td>
            <td><a href="?delete=' . $row['id'] . '">
            <button type="button" class="btn btn-danger">Delete</button>
            </a></td>
        </tr>
       ';
        }
        return $htmlCode;
    }

    function RemoveMovieByUser($id, $uid){
        $stmt = $this->db->prepare('DELETE FROM movies WHERE id = :id AND user_id = :userid ');
        $stmt->bindParam('id', $id);
        $stmt->bindParam('userid', $uid);
        $stmt->execute();
    }

    /**
     * The function GetUpcomingMovies retrieves all movies that are releasing within the next 24 hours.
     * 
     * @return an array of upcoming movies that have a release date within the next 24 hours.
     */
    function GetUpcomingMovies(): mixed
    {
        $stmt = $this->db->prepare('SELECT *
        FROM movies
        WHERE release_date >= NOW() AND release_date <= DATE_ADD(NOW(), INTERVAL 72 HOUR)
        AND email_sent IS NULL;
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * The function "reminderSent" updates the "email" column of the "movies" table to 1 for the
     * specified "id".
     * 
     * @param id The "id" parameter is the unique identifier of a movie in the database.
     */
    function reminderSent($id): void
    {

        $stmt = $this->db->prepare('UPDATE movies SET email_sent = 1, updated_at = NOW() WHERE id = :id');
        $stmt->bindParam('id', $id);
        $stmt->execute();
    }


    /**
     * The function `getMovieRelease` retrieves the release date of a movie based on its ID using the
     * TMDB API.
     * 
     * @param int movie_id The movie_id parameter is an integer that represents the unique identifier
     * of a movie. It is used to retrieve the release date of a specific movie from the API.
     * 
     * @return string the release date of a movie with the given movie ID.
     */
    private function getMovieRelease(int $movie_id): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.themoviedb.org/3/movie/' . $movie_id . '/release_dates?api_key=d679741f03a2925a326fb72686aa6130',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer d679741f03a2925a326fb72686aa6130'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response, true);
        $response = $response['results'];
        $release_date = '';
        foreach ($response as $resp) {
            if ($resp['iso_3166_1'] == 'US') {
                $release_date = $resp['release_dates'][0]['release_date'];
                break;
            }
        }
        return $release_date;
    } // End of function getmovierelease

    /**
     * The function `getMovieName` makes a GET request to the TMDB API to retrieve the original title
     * of a movie based on its ID.
     * 
     * @param int movie_id The movie_id parameter is an integer that represents the unique identifier
     * of a movie.
     * 
     * @return string the original title of a movie.
     */
    private function getMovieName(int $movie_id): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.themoviedb.org/3/movie/' . $movie_id . '?api_key=d679741f03a2925a326fb72686aa6130',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer d679741f03a2925a326fb72686aa6130'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $resp = json_decode($response, true);
        return $resp['original_title'];
    }
}
