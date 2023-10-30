<?php
require_once __DIR__ . '\database.php';

class User {
    public $name;
    public $login;
    private $password;

    /**
     * Метод фильтрует, проверяет и записывает в базу данных данные, введённые в форму регистрации
     * @param string $name
     * @param string $login
     * @param string $pass
     * @return array
     */
    public function registration(string $name, string $login, string $pass) : array
    {
        $this->name = filter_var(trim($name), FILTER_UNSAFE_RAW);
        $this->login = filter_var(trim($login), FILTER_UNSAFE_RAW);
        $this->password = filter_var(trim($pass), FILTER_UNSAFE_RAW);

        if(mb_strlen($this->name) < 4 || mb_strlen($this->name) > 32)
        {
            echo "Недопустимая длина логина. Логин должен содержать от 4 до 16 символов.";
            exit();
        }
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
        $sql = "SELECT login FROM users WHERE login = '$this->login'";
        $loginName = $db->dbSelectQuery($sql);
        if (!empty($loginName))
        {
            return array(false ,"Пользователь с таким логином уже зарегистрирован в системе.");
        }

        $sql = "INSERT INTO users (name, login, password) VALUES ('$this->name', '$this->login', '$this->password')";
        $db->dbQuery($sql);

        setcookie('user', $this->login, time() + 3600, "/");

        return array(true, "Пользователь зарегистрирован и авторизован.");
    }

    /**
     * Метод фильтрует и проверяет данные, введённые в форму авторизации
     * @param string $login
     * @param string $pass
     * @return array
     */
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
        $loginName = $db->dbSelectQuery($sql);

        if (isset($loginName)) {
            setcookie('user', $loginName[0]['login'], time() + 3600, "/");

            return true;
        }

        return false;
    }

    /**
     * Метод получения из базы данных информации о хозяевах с животными старше указанного возраста (по умолчанию 3 года)
     * @param int $year
     * @return array|null
     */
    public function getOwnerQuery(int $year = 3) : array|null
    {
        $db = new DataBase();
        $sql = "SELECT u.name, p.nickname, p.age FROM owners u 
                LEFT JOIN owners_pets up ON up.owner_id = u.id 
                LEFT JOIN pets p ON up.pet_id = p.id 
                WHERE p.age > '$year'";
        $result = $db->dbSelectQuery($sql);

        return $result;
    }
}