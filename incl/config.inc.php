<?php

include 'classes/database.class.php';
include 'classes/user.class.php';

$DB_USER = 'root';
$DB_PASSWORD = 'Change.me.12';
$DB_HOST = 'localhost';
$DB_NAME = 'cinemasync';
$AES_KEY = 'ptLYlgD7oAaiorhS7XIUeMDdAVuKZVkWvfdSZAPc1Zc4D6pAB8'; //Change this (Use a random generated String)
$HASHED_AES_KEY = hash('sha256', $AES_KEY); //Don#t change this
$SUB_FOLDER = 'gen/'; //If the template doesn't run in a sub folder leave this empty

$db = new Database($DB_NAME, $DB_HOST,$DB_USER, $DB_PASSWORD, $HASHED_AES_KEY);
session_start();
