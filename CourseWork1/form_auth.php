<?php
    //Подключение шапки
    require_once("index.php");
?>
<!-- Блок для вывода сообщений -->
<div class="block_for_messages">
    <?php
        //Если в сессии существуют сообщения об ошибках, то выводим их
        if(isset($_SESSION["error_messages"]) && !empty($_SESSION["error_messages"])){
            echo $_SESSION["error_messages"];
 
            //Уничтожаем чтобы не выводились заново при обновлении страницы
            unset($_SESSION["error_messages"]);
        }
 
        //Если в сессии существуют радостные сообщения, то выводим их
        if(isset($_SESSION["success_messages"]) && !empty($_SESSION["success_messages"])){
            echo $_SESSION["success_messages"];
             
            //Уничтожаем чтобы не выводились заново при обновлении страницы
            unset($_SESSION["success_messages"]);
        }
    ?>
</div>
 
<?php
    //Проверяем, если пользователь не авторизован, то выводим форму регистрации, 
    //иначе выводим сообщение о том, что он уже зарегистрирован
    if(!isset($_SESSION["email"]) && !isset($_SESSION["password"])){
?>
        <div id="form_auth">
            <h2>Форма регистрации</h2>
 
            <form action="register.php" method="post" name="form_auth">
                <table>
                <tbody><tr>
                    <td> Email: </td>
                    <td>
                        <input type="email" name="email" required="required"><br>
                        <span id="valid_email_message" class="mesage_error"></span>
                    </td>
                </tr>
          
                <tr>
                    <td> Пароль: </td>
                    <td>
                        <input type="password" name="password" placeholder="минимум 6 символов" required="required"><br>
                        <span id="valid_password_message" class="mesage_error"></span>
                    </td>
                </tr>
                 
                <tr>
                    <td> Введите капчу: </td>
                    <td>
                        <p>
                            <img src="captcha.php" alt="Изображение капчи" /> <br>
                            <input type="text" name="captcha" placeholder="Проверочный код">
                        </p>
                    </td>
                </tr>
 
                <tr>
                    <td colspan="2">
                        <input type="submit" name="btn_submit_auth" value="Войти">
                    </td>
                </tr>
            </tbody></table>
            </form>
        </div>
<?php
    }else{
?>
        <div id="authorized">
            <h2>Вы успешно прошли аутентификацию</h2>
        </div>
<?php
    }
     
    //Подключение подвала
    require_once("index.php");
?>
<?php
        //Запускаем сессию
        session_start();
 
        //Добавляем файл подключения к БД
        require_once("dbconnect.php");
        //Объявляем ячейку для добавления ошибок, которые могут возникнуть при обработке формы.
$_SESSION["error_messages"] = '';
 
//Объявляем ячейку для добавления успешных сообщений
$_SESSION["success_messages"] = '';
/*
    Проверяем была ли отправлена форма, то есть была ли нажата кнопка Войти. Если да, то идём дальше, если нет, то выведем пользователю сообщение об ошибке, о том что он зашёл на эту страницу напрямую.
*/
if(isset($_POST["btn_submit_auth"]) && !empty($_POST["btn_submit_auth"])){
 
    //(1) Место для следующего куска кода
 
}else{
    exit("<p><strong>Ошибка!</strong> Вы зашли на эту страницу напрямую, поэтому нет данных для обработки. Вы можете перейти на <a href=".$address_site."> главную страницу </a>.</p>");
}
//Обрезаем пробелы с начала и с конца строки
$email = trim($_POST["email"]);
if(isset($_POST["email"])){
 
    if(!empty($email)){
        $email = htmlspecialchars($email, ENT_QUOTES);
 
        //Проверяем формат полученного почтового адреса с помощью регулярного выражения
        $reg_email = "/^[a-z0-9][a-z0-9\._-]*[a-z0-9]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+/i";
 
        //Если формат полученного почтового адреса не соответствует регулярному выражению
        if( !preg_match($reg_email, $email)){
            // Сохраняем в сессию сообщение об ошибке. 
            $_SESSION["error_messages"] .= "<p class='mesage_error' >Вы ввели неправильный email</p>";
             
            //Возвращаем пользователя на страницу авторизации
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$address_site."/form_auth.php");
 
            //Останавливаем скрипт
            exit();
        }
    }else{
        // Сохраняем в сессию сообщение об ошибке. 
        $_SESSION["error_messages"] .= "<p class='mesage_error' >Поле для ввода почтового адреса(email) не должна быть пустой.</p>";
         
        //Возвращаем пользователя на страницу регистрации
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$address_site."/form_auth.php");
 
        //Останавливаем скрипт
        exit();
    }
     
 
}else{
    // Сохраняем в сессию сообщение об ошибке. 
    $_SESSION["error_messages"] .= "<p class='mesage_error' >Отсутствует поле для ввода Email</p>";
     
    //Возвращаем пользователя на страницу авторизации
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$address_site."/form_auth.php");
 
    //Останавливаем скрипт
    exit();
}
 
//(3) Место для обработки пароля
if(isset($_POST["password"])){
 
    //Обрезаем пробелы с начала и с конца строки
    $password = trim($_POST["password"]);
 
    if(!empty($password)){
        $password = htmlspecialchars($password, ENT_QUOTES);
 
        //Шифруем пароль
        $password = md5($password."top_secret");
    }else{
        // Сохраняем в сессию сообщение об ошибке. 
        $_SESSION["error_messages"] .= "<p class='mesage_error' >Укажите Ваш пароль</p>";
         
        //Возвращаем пользователя на страницу регистрации
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$address_site."/form_auth.php");
 
        //Останавливаем скрипт
        exit();
    }
     
}else{
    // Сохраняем в сессию сообщение об ошибке. 
    $_SESSION["error_messages"] .= "<p class='mesage_error' >Отсутствует поле для ввода пароля</p>";
     
    //Возвращаем пользователя на страницу регистрации
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$address_site."/form_auth.php");
 
    //Останавливаем скрипт
    exit();
}
//Запрос в БД на выборке пользователя.
$result_query_select = $mysqli->query("SELECT * FROM `users` WHERE email = '".$email."' AND password = '".$password."'");
 
if(!$result_query_select){
    // Сохраняем в сессию сообщение об ошибке. 
    $_SESSION["error_messages"] .= "<p class='mesage_error' >Ошибка запроса на выборке пользователя из БД</p>";
     
    //Возвращаем пользователя на страницу регистрации
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$address_site."/form_auth.php");
 
    //Останавливаем скрипт
    exit();
}else{
 
    //Проверяем, если в базе нет пользователя с такими данными, то выводим сообщение об ошибке
    if($result_query_select->num_rows == 1){
         
        // Если введенные данные совпадают с данными из базы, то сохраняем логин и пароль в массив сессий.
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
 
        //Возвращаем пользователя на главную страницу
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$address_site."/index.php");
 
    }else{
         
        // Сохраняем в сессию сообщение об ошибке. 
        $_SESSION["error_messages"] .= "<p class='mesage_error' >Неправильный логин и/или пароль</p>";
         
        //Возвращаем пользователя на страницу авторизации
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$address_site."/form_auth.php");
 
        //Останавливаем скрипт
        exit();
    }
}