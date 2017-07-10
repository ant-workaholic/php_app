<?php
require_once("vendor/autoload.php");
define("DS", DIRECTORY_SEPARATOR);
define('DIR_BASE', dirname( __FILE__ ));

#ini_set("error_reporting", 0);
\Core\Front\Controller::run();
