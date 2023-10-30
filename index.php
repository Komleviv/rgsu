<?php
require_once 'src/database.php';
require_once 'src/user.php';
require_once 'src/file.php';

$user = new User();
$infoString = "";
$isLogin = false;
$isRegister = false;

if (isset($_POST['login'], $_POST['pass'])) {
    if (isset($_GET['reg'])) {
        $regStatus = $user->registration($_POST['name'], $_POST['login'], $_POST['pass']);
        $isRegister = $regStatus[0];
        $infoString = $regStatus[1];
    } else {
        $isLogin = $user->authorization($_POST['login'], $_POST['pass']);
        if ($isLogin)
            $infoString = "Пользователь авторизован.";
    }
}

if (isset($_FILES['xml'])) {
    $file = new File();

    if (isset($_COOKIE['user'])) {
        $xmlArray = $file->xmlParseAndQuery($_FILES['xml']['tmp_name']);

        if ($xmlArray) {
            $infoString = "Данные из XML успешно добавлены в базу данных.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="src/style.css" type="text/css">
    <title>Список животных</title>
</head>
<body class="body-bg">
    <div class="container">
        <div class="alert alert-success">
            <?= $infoString ?>
        </div>
        <?php if (isset($_COOKIE['user']) || $isLogin || $isRegister): ?>
            <div class="xml-parsing">
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <label for="xml">Выберите XML-файл для загрузки:</label>
                    <input type="file" id="xml" name="xml" accept=".xml" />
                    <button class="btn btn-success" type="submit">Загрузить XML</button>
                </form>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col" style="width: 25em">
                    <form action="index.php?reg=1" method="post">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Укажите своё имя"><br>
                        <input type="text" class="form-control" name="login" id="login" placeholder="Придумайте логин"><br>
                        <input type="password" class="form-control" name="pass" id="pass" placeholder="Придумайте пароль"><br>
                        <button class="btn btn-success" type="submit">Зарегистрироваться</button>
                    </form>
                </div>
                <div class="col" style="width: 25em">
                    <form action="index.php" method="post">
                        <input type="text" class="form-control" name="login" id="login" placeholder="Введите логин"><br>
                        <input type="password" class="form-control" name="pass" id="pass" placeholder="Введите пароль"><br>
                        <button class="btn btn-success" type="submit">Авторизоваться</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <div class="owner-pets">
            <h5>Люди, у которых есть питомцы старше 3 лет:</h5>
            <div class="card-body">
                <?php
                $ownersName = $user->getOwnerQuery();
                if(isset($ownersName)) {
                    foreach ($ownersName as $owner) {
                ?>
                <div class="row">
                    <div class="col">
                        <?= $owner['name'] ?>
                    </div>
                    <div class="col">
                        <?= $owner['nickname'] ?>
                    </div>
                    <div class="col">
                        <?= $owner['age'] ?>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>