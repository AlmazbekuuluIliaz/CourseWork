<?php
    //Запускаем сессию
    session_start();
?>
 
<!DOCTYPE html>
<html>
    <head>
        <title>Ваша регистрация</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <div id="header">
            <h2>Шапка сайта</h2>
 
            <div id="auth_block">
 
                <div id="link_auth">
                    <a href="/form_auth.php">Аутентификация</a>
                </div>
 
            </div>
             <div class="clear"></div>
        </div>
        <div id="footer">
            <h2>Подвал сайта</h2>
        </div>
        <div id="auth_block">
        <?php
        //Проверяем авторизован ли пользователь
        if(!isset($_SESSION['email']) && !isset($_SESSION['password'])){
            // если нет, то выводим блок с ссылками на страницу регистрации и авторизации
        ?>
            <div id="link_register">
                <a href="/form_auth.php">Регистрация</a>
            </div>

            <div id="link_auth">
                <a href="/form_auth.php">Авторизация</a>
            </div>
        <?php
        }
?>
    </body>
</html>