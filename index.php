<?php
require_once("vendor/autoload.php");
define("DS", DIRECTORY_SEPARATOR);
define('DIR_BASE', dirname( __FILE__ ));

\Core\Front\Controller::run();
