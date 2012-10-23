<?php

class Session {

    public function createSession($name, $val) {

        $_SESSION[$name] = $val;
        return $this;
    }

    public function selectSession($name) {

        return $_SESSION[$name];
    }

    public function checkSession($name) {

        return isset($_SESSION[$name]);
    }

    public function deleteSession($name) {

        unset($_SESSION[$name]);
        return $this;
    }

}

?>