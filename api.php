<?php
header('Content-Type: text/plain');


require('incl/config.inc.php');


//use the format ?a=action&param1=value1&param2=value2

// get the action from the request
if (!isset($_REQUEST['a'])) {
    die('No action specified');
}
$user = $db->getUser($_SESSION['email']);
if ($user === null) {
    die('User not logged in');
}
//save to variable
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
    case 'loggedin':
        
        break;

    default:
        die('Invalid action');
        break;
}
