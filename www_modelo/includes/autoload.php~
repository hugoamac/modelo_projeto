<?php

session_start();

/*Para acesso Local*/
define("BANCO", "LOCAL");
define("HOST", "localhost");
define("DATABASE", "nome do banco");
define("USER", "nome do usuario");
define("PASS", "senha do banco");

/*Para acesso onlilne*/
/*
define("BANCO", "ONLINE");
define("HOST_ONLINE", "localhost");
define("DATABASE_ONLINE", "nome do banco");
define("USER_ONLINE", "nome do usuario");
define("PASS_ONLINE", "senha do banco");*/

function __autoload($class) {

    if (is_file($_SERVER["DOCUMENT_ROOT"] . "/classes/" . $class . ".php")) {

        require_once($_SERVER["DOCUMENT_ROOT"] . "/classes/" . $class . ".php");
    } else {

        die("A classe : {$class} não  existe!");
    }
}

?>
