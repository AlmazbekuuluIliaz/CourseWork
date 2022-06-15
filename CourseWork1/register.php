<?php
    //Запускаем сессию
    session_start();
 
    //Добавляем файл подключения к БД
    require_once("connect.php");
 
    //Объявляем ячейку для добавления ошибок, которые могут возникнуть при обработке формы.
    $_SESSION["error_messages"] = '';
 
    //Объявляем ячейку для добавления успешных сообщений
    $_SESSION["success_messages"] = '';
        if(isset($_POST["email"])){
 
            //Обрезаем пробелы с начала и с конца строки
            $email = trim($_POST["email"]);
         
            if(!empty($email)){
         
                $email = htmlspecialchars($email, ENT_QUOTES);
                //Проверяем формат полученного почтового адреса с помощью регулярного выражения
$reg_email = "/^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i";
 
//Если формат полученного почтового адреса не соответствует регулярному выражению
if( !preg_match($reg_email, $email)){
    // Сохраняем в сессию сообщение об ошибке. 
    $_SESSION["error_messages"] .= "<p class='mesage_error' >Вы ввели неправельный email</p>";
     
    //Возвращаем пользователя на страницу регистрации
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$address_site."/form_register.php");
 
    //Останавливаем  скрипт
    exit();
}
 
//Проверяем нет ли уже такого адреса в БД.
$result_query = $mysqli->query("SELECT `email` FROM `users` WHERE `email`='".$email."'");
 
//Если кол-во полученных строк ровно единице, значит пользователь с таким почтовым адресом уже зарегистрирован
if($result_query->num_rows == 1){
 
    //Если полученный результат не равен false
    if(($row = $result_query->fetch_assoc()) != false){
         
            // Сохраняем в сессию сообщение об ошибке. 
            $_SESSION["error_messages"] .= "<p class='mesage_error' >Пользователь с таким почтовым адресом уже зарегистрирован</p>";
             
            //Возвращаем пользователя на страницу регистрации
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$address_site."/form_register.php");
         
    }else{
        // Сохраняем в сессию сообщение об ошибке. 
        $_SESSION["error_messages"] .= "<p class='mesage_error' >Ошибка в запросе к БД</p>";
         
        //Возвращаем пользователя на страницу регистрации
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$address_site."/form_register.php");
    }
 
    /* закрытие выборки */
    $result_query->close();
 
    //Останавливаем  скрипт
    exit();
}
//Запрос на добавления пользователя в БД
$result_query_insert = $mysqli->query("INSERT INTO `users` (first_name, last_name, email, password) VALUES ('".$first_name."', '".$last_name."', '".$email."', '".$password."')");
 
if(!$result_query_insert){
    // Сохраняем в сессию сообщение об ошибке. 
    $_SESSION["error_messages"] .= "<p class='mesage_error' >Ошибка запроса на добавления пользователя в БД</p>";
     
    //Возвращаем пользователя на страницу регистрации
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$address_site."/form_auth.php");
 
    //Останавливаем  скрипт
    exit();
}else{
 
    $_SESSION["success_messages"] = "<p class='success_message'Аутентификация прошла успешно!!! <br /></p>";
 
    //Отправляем пользователя на страницу авторизации
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$address_site."/form_auth.php");
}
 
/* Завершение запроса */
$result_query_insert->close();
 
//Закрываем подключение к БД
$mysqli->close();
/* закрытие выборки */
$result_query->close();
                // (3) Место кода для проверки формата почтового адреса и его уникальности
         
            }else{
                // Сохраняем в сессию сообщение об ошибке. 
                $_SESSION["error_messages"] .= "<p class='mesage_error'>Укажите Ваш email</p>";
                 
                //Возвращаем пользователя на страницу регистрации
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ".$address_site."/form_auth.php");
         
                //Останавливаем  скрипт
                exit();
            }
         
        }else{
            // Сохраняем в сессию сообщение об ошибке. 
            $_SESSION["error_messages"] .= "<p class='mesage_error'>Отсутствует поле для ввода Email</p>";
             
            //Возвращаем пользователя на страницу регистрации
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$address_site."/form_auth.php");
         
            //Останавливаем  скрипт
            exit();
        }
         
         
        if(isset($_POST["password"])){
         
            //Обрезаем пробелы с начала и с конца строки
            $password = trim($_POST["password"]);
         
            if(!empty($password)){
                $password = htmlspecialchars($password, ENT_QUOTES);
         
                //Шифруем папроль
                $password = md5($password."top_secret"); 
            }else{
                // Сохраняем в сессию сообщение об ошибке. 
                $_SESSION["error_messages"] .= "<p class='mesage_error'>Укажите Ваш пароль</p>";
                 
                //Возвращаем пользователя на страницу регистрации
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ".$address_site."/form_auth.php");
         
                //Останавливаем  скрипт
                exit();
            }
         
        }else{
            // Сохраняем в сессию сообщение об ошибке. 
            $_SESSION["error_messages"] .= "<p class='mesage_error'>Отсутствует поле для ввода пароля</p>";
             
            //Возвращаем пользователя на страницу регистрации
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$address_site."/form_auth.php");
         
            //Останавливаем  скрипт
            exit();
        }
?>