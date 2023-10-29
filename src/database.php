<?php
class DataBase {

    public $db;

    function __construct()
    {
        $config = require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

        $this->db = new mysqli($config['servername'], $config['username'], $config['password'], $config['database']);

        if ($this->db->errno) {
            echo 'Ошибка в подключении к базе данных (' . $this->db->errno. '): ' . $this->db->error;
            exit();
        }
    }

    public function dbQuery($sql): array|null
    {
        $query = $this->db->query($sql);

        $result = $query->fetch_assoc();

        return $result;
    }

    function __destruct()
    {
        $this->db->close();
    }
}
