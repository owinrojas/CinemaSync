<?php
if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');

// loop for each of the reminders in the database, check the date
// if the time is now after the release date or close to the release date,
// send out an email reminder to the user.