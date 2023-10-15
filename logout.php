<?php

require "incl/config.inc.php"; // require config
session_destroy(); // destroy session
header("Location: login.php"); // redirect to login page