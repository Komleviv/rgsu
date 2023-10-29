<?php
require_once __DIR__ . '\database.php';

class User {
    public $name;
    public $login;
    private $password;
    private $role;

    public function authorization(string $login, string $pass) : bool
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

    public function getUserQuery(int $year = 3) : array|null
    {
        $db = new DataBase();
        $sql = "SELECT u.name FROM users u 
                LEFT JOIN users_pets up ON up.user_id = u.id 
                LEFT JOIN pets p ON up.pet_id = p.id 
                WHERE p.age > '$year'";
        $usersName = $db->dbQuery($sql);

        return $usersName;
    }
}