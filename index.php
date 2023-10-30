<?php
require_once 'src/database.php';
require_once 'src/user.php';
require_once 'src/file.php';

$user = new User();
$isLogin = false;

if (isset($_POST['login'], $_POST['pass'])) {
    $isLogin = $user->authorization($_POST['login'], $_POST['pass']);
    if ($isLogin)
        echo "Пользователь авторизован.";
}

if (isset($_FILES['xml'])) {
    $file = new File();
    $xmlArray = $file->xmlParse($_FILES['xml']['tmp_name']);
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&subset=latin,cyrillic" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="src/style.css" type="text/css">
    <title>Список животных</title>
</head>
<body>
    <div class="container">
        <?php
            if (isset($_COOKIE['user']) || $isLogin):
        ?>
            <form action="index.php" method="post" enctype="multipart/form-data">
                <label for="xml">Выберите XML-файл для загрузки:</label>
                <input type="file" id="xml" name="xml" accept=".xml" />
                <button class="btn btn-success" type="submit">Загрузить XML</button>
            </form>
        <?php
            else:
        ?>
            <form action="index.php" method="post">
                <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин"><br>
                <input type="password" class="form-control" name="pass" id="pass" placeholder="Введите пароль"><br>
                <button class="btn btn-success" type="submit">Авторизоваться</button>
            </form>
        <?php
            endif;
        ?>
    </div>
    <div class="table">
        <h5>Люди, у которых есть питомцы старше 3 лет:</h5>
        <?php
            $ownersName = $user->getUserQuery();
            if(isset($ownersName)) {
                foreach ($ownersName as $name) {
                    echo $name;
                }
            }
        ?>
    </div>
</body>
</html>