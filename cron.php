<?php
header('Content-Type: text/plain');
if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');


require('incl/config.inc.php');

try {
    $movies = $db->GetUpcomingMovies();
    foreach ($movies as $movie) {
        // get the user by ID
        $user = $db->getUserById($movie['user_id']);
        //prepare the email
        $email = new Email($EMAIL_SENDER, $user->getData('email'));
        //send the reminder email
        $status = $email->reminderEmail($movie['movie_name'], $movie['release_date']);

        if ($status) {
            $db->reminderSent($movie['id']);
        } else {
            throw new Exception("Error sending email"); // throw exception if email not sent.
        }
    }
    echo "Cron job executed successfully";
} catch (Exception $e) {
    die($e->getMessage());
}
