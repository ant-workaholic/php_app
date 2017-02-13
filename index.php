<?php
require("src/Core/App/Exception/Base.php");
require("src/Core/App/Registry/RegistryAbstract.php");
require("src/Core/App/Registry/ApplicationRegistry.php");
require("src/Core/App/Helper/ApplicationHelper.php");
require("src/Core/App/Front/Controller.php");


\Core\App\Controller::run();


//\Core\App\Registry\ApplicationRegistry::setDNS("Test");
//function customError($errno, $errstr)
//{
//    echo "<b style='color:orangered'> Error </b> [$errno] $errstr <br>";
//    echo "Ending Script";
//    die();
//}
//
//set_error_handler("customError");


//if (!file_exists("test.csv")) {
//    die("The file is not exists!");
//}
//$file = fopen("test.csv", "r");
//
//$test=2;
//if ($test>=1) {
//    trigger_error("Value must be 1 or below", E_USER_WARNING  );
//}
//error handler function
//function customError($errno, $errstr) {
//    echo "<b>Error:</b> [$errno] $errstr<br>";
//    echo "Webmaster has been notified";
//    error_log("Error: [$errno] $errstr",1,
//        "someone@example.com","From: igorchernin@yahoo.com");
//}
//
////set error handler
//set_error_handler("customError",E_USER_WARNING);
//
////trigger error
//$test=2;
//if ($test>=1) {
//    trigger_error("Value must be 1 or below",E_USER_WARNING);
//}
//function test($str) {
//    echo $str;
//    var_dump(debug_backtrace());
//}
//
//test("Test");