<?php
//autoload classes
require_once "autoloader.php";

//Start Session
session_start();

//include the main configuration file
require_once "config/config.php";

//include helper functions
require_once "helper.php";

//define global constants
define("APP_NAME", "CMS PDO System");
define("PROJECT_DIRECTORY","cms");
?>