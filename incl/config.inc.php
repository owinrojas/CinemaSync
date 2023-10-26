<?php

include 'classes/database.class.php'; // database class
include 'classes/user.class.php'; // user class

// TODO: imdb / tmdb class
// NOTE: can use an external library


$DB_HOST = 'localhost'; // usually localhost
$DB_NAME = 'cinemasync'; // database name
$DB_USER = 'root'; // database username
$DB_PASSWORD = 'Change.me.12'; // database password

$db = new Database($DB_NAME, $DB_HOST, $DB_USER, $DB_PASSWORD); // create database object
session_start(); // start session
