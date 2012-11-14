<?php

error_reporting(E_ALL ^ E_NOTICE);

session_start();

define("BANCO", "LOCAL");
define("HOST", "localhost");
define("DATABASE", "paulo");
define("USER", "root");
define("PASS", "lordswxp");

function __autoload($class) {

    if (is_file($_SERVER["DOCUMENT_ROOT"] . "/classes/" . $class . ".php")) {

        require_once($_SERVER["DOCUMENT_ROOT"] . "/classes/" . $class . ".php");
    } else {

        die("A classe : {$class} não  existe!");
    }
}


$excessao = array();
$_POST = Helper::filtroHTTP($_POST,$excessao);
$_GET = Helper::filtroHTTP($_GET,$excessao);
?>
