<?php

if (!Auth::getInstance()->checkLogin()) {

    header("Location:index.php");
    exit;
}
?>
