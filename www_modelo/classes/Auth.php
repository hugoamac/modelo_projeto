<?php

class Auth {

    protected $session;
    protected $tablename, $userCollum, $passCollum;
    protected $user, $pass;
    protected static $_instance;

    public function __construct() {

        $this->session = new Session();


        return $this;
    }

    public static function getInstance() {

        if (null === self::$_instance) {

            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setTableName($tb) {

        $this->tablename = $tb;
        return $this;
    }

    public function setUserCollum($col) {

        $this->userCollum = $col;
        return $this;
    }

    public function setPassCollum($col) {

        $this->passCollum = $col;
        return $this;
    }

    public function setUser($name) {

        $this->user = $name;
        return $this;
    }

    public function setPass($pass) {

        $this->pass = $pass;
        return $this;
    }

    public function login() {

        $db = new Crud();
        $db->table = $this->tablename;

        $where = array(
            $this->userCollum => $this->user,
            $this->passCollum => $this->pass
        );

        $data = $db->find($where);

        if ($data) {

            $this->session->createSession('userAuth', true);
            $this->session->createSession('userData', $data);

            return true;
        } else {


            return false;
        }
    }

    public function logout() {

        $this->session->deleteSession('userAuth')
                ->deleteSession('userData');



        return true;
    }

    public function checkLogin($action = "boolean") {

        switch ($action) {

            case "boolean" :

                if (!$this->session->checkSession('userAuth'))
                    return false;
                else
                    return true;

                break;

            case "stop":
                if (!$this->session->checkSession('userAuth'))
                    exit;

                break;
        }
    }

    public function userData($name) {

        $data = $this->session->selectSession('userData');

        return $data[$name];
    }

}

?>