<?php
header('Content-Type: text/plain');
require('incl/config.inc.php');


//use the format ?a=action&param1=value1&param2=value2

// get the action from the request
if (!isset($_REQUEST['a'])) {
    http_response_code(400); // 400 bad request code
    die('No action specified');
}
$user = $db->getUser($_SESSION['email']);
if ($user === null) {
    http_response_code(401); // 401 forbidden code
    die('User not logged in');
}
//save action to variable
$action = $_REQUEST['a'];

// get the parameters remove the action from array
$params = $_REQUEST;
unset($params['a']);


// switch statement based on action
switch ($action) {
    case 'addmovie':
        try {
            $status =  $db->addMovie($user->id, $params['movie_id']);
        } catch (Exception $e) {
            die($e->getMessage());
        }
        if ($status) {
            echo 'Movie saved';
        } else {
            echo 'Movie already saved';
        }

        break;
    case 'checkmovie':

        break;

    default:
        http_response_code(400); // 400 bad request code
        die('Invalid action');
        break;
}
