<?php

include 'classes/database.class.php'; // database class
include 'classes/user.class.php'; // user class
include 'classes/email.class.php'; // email class


$DB_HOST = 'localhost'; // usually localhost
$DB_NAME = 'movie'; // database name
$DB_USER = 'root'; // database username
$DB_PASSWORD = ''; //database password
$EMAIL_SENDER = 'movie@movies.slakjd.com'; // set the sender email

$db = new Database($DB_NAME, $DB_HOST, $DB_USER, $DB_PASSWORD); // create database object
session_start(); // start session
