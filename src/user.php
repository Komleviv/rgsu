<?php
require_once __DIR__ . '\database.php';

class User {
    public $name;
    public $login;
    private $password;
    private $role;

    public function authorization($login, $pass) : bool
    {
        $this->login = filter_var(trim($login), FILTER_UNSAFE_RAW);
        $this->password = filter_var(trim($pass), FILTER_UNSAFE_RAW);

        if(mb_strlen($this->login) < 4 || mb_strlen($this->login) > 16)
        {
            echo "Недопустимая длина логина. Логин должен содержать от 4 до 16 символов.";
            exit();
        }
        if(mb_strlen($this->password) < 5 || mb_strlen($this->password) > 32)
        {
            echo "Недопустимая длина пароля. Пароль должен содержать от 5 до 32 символов.";
            exit();
        }

        $this->password = md5($this->password."rgsurgsu123");

        $db = new DataBase();
        $sql = "SELECT login FROM users WHERE login = '$this->login' AND password = '$this->password'";
        $loginName = $db->dbQuery($sql);

        if (isset($loginName)) {
            setcookie('user', $loginName['login'], time() + 3600, "/");

            return true;
        }

        return false;
    }

}