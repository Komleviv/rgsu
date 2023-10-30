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

    public function dbSelectQuery($sql): array|null
    {
        $query = $this->db->query($sql);

        if (!$query) {
            echo "Ошибка: " . $this->db->error;
        }

        $result = $query->fetch_assoc();

        return $result;
    }

    public function dbQuery($sql): void
    {
        $query = $this->db->query($sql);

        if (!$query) {
            echo "Ошибка: " . $this->db->error;
        }
    }

    public function lastIdQuery($table): int
    {
        $sql = "SELECT id FROM `$table` ORDER BY id DESC LIMIT 1";
        $query = $this->db->query($sql);

        $result = $query->fetch_assoc();

        return empty($result) ? 1 : $result[0]+1;
    }

    function __destruct()
    {
        $this->db->close();
    }
}
