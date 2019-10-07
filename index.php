<?php
// error_reporting(0); //Протокол ошибок выключен
ini_set('display_errors',1); //Включаем ошибка в конфигурации PHP
error_reporting(-1); //Вывод всех ошибок
include_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
if (!isset($_COOKIE['login'])){
  echo '
  <!DOCTYPE html>
  <html lang="ru">
    <head>
      <link rel="manifest" href="manifest.json">
      <script src="manup.js"></script>
      <meta name="Description" content="TableTask">
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
      <meta name="theme-color" content="#317EFB"/>
      <link rel="stylesheet" href="css/logform.css">
      <title>Учет Проектов</title>
    </head>
    <body>
    <div class="needhide">
    </div>
      <div class="showon">
        <div class="container">
          <form action="enter.php" method="POST">
            <span class="heading"><img alt="logoimg" src="/image/loginicon2.png" class="logoimglogin">
            <br>
            Учет <br>Проектов</span>
            <ul class="flex-outer">
            <li>
              <input type="text" class="inputstyle" name="login" id="inputlogin" placeholder="Логин">
            </li>
            <li>
              <input type="password" class="inputstyle" name="pass" id="inputpass" placeholder="Пароль">
            </li>
            </ul> 
            <ul class="flex-outer">
              <li>
              <button class="inputstyle-btn" type="submit">Вход</button>
            </li>
            </ul>  
          </form>
        </div>
      </div>
  ';
echo '
    <div class="footer-form">
      <p>ver.: 0.1(a)</p>
      <p>Разработано - © АО &quot;Гипроздрав&quot; '; echo date("Y");echo '</p>
    </div>
    </body>
  </html>
      ';

}

if(empty($_COOKIE['login'])){

}else{

  include(__DIR__.'/pages/index.php');
}

?>




