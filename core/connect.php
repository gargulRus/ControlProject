<?php

//функция обработки mysql запросов
function query($query){ global $link; //Название твоего соединения    
    if($response=mysqli_query($link, $query)){
        return $response;
    }
    return false; 
}


// $link = mysqli_connect("192.168.62.231", "root", "1") or
$link = mysqli_connect("192.168.62.33", "root", "3") or
    die("Ошибка соединения: " . mysql_error());
mysqli_select_db($link, "abigordatabase");

//mysqli_query($link, "SET NAMES 'UTF8'");
query("SET NAMES 'UTF8'");

//mysqli_set_charset( 'utf8' );
?>