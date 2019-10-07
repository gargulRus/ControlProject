<?php
$login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
include_once($_SERVER['DOCUMENT_ROOT']."/core/connect.php");
if(empty($login) or empty($pass)){
      exit('
  <div class="formposition">
    <div class="container">
      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <form class="form-horizontal" action="enter.php" method="POST">
            <span class="heading"> <img src="/image/logo.png" class="logoimglogin">
                          <br>
              Учет <br>Проектов</span>
            <div class="form-group">
          <p><h3>Логин или пароль не введен!</h3></p>
            </div>
            <div class="form-group">
            <input type="button" class="btn btn-default" value="Назад" onclick="history.back()">
        
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
      ');
}
else{
$toAcc = query("SELECT * FROM autent WHERE login='".$login."'");
$accData = mysqli_fetch_array($toAcc);
// var_export($accData);
if($accData['pass']==NULL){
    exit('
    <div class="formposition">
    <div class="container">
      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <form class="form-horizontal" action="enter.php" method="POST">
            <span class="heading"> <img src="/image/logo.png" class="logoimglogin">
                         <br>
             Учет <br>Проектов</span>
            <div class="form-group">
          <p><h3>Вы не ввели пароль!</h3></p>
            </div>
            <div class="form-group">
            <input type="button" class="btn btn-default" value="Назад" onclick="history.back()">
        
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
    ');
}else{
    if($accData['pass']==$pass){
    // echo "Авторизация успешна!";
    // echo "<input type='button' value='Назад' onclick='history.back()'>";
    setcookie("login", $accData['login'], time()+3600*24*365);
    setcookie("role_id", $accData['role_id'], time()+3600*24*365);
    setcookie("exe_id", $accData['exe_id'], time()+3600*24*365);
    setcookie("name", $accData['name'], time()+3600*24*365);
    echo '
      <link rel="stylesheet" href="css/logform.css">
      <link rel="stylesheet" href="css/main.css">
      <link rel="stylesheet" href="css/logform.css">
    ';
    echo'
    <body>
    <div class="formpositionlog">
    <div class="container"> 
    <p><img src="/image/sample23.gif" alt="Пример" width="400" height="375"></p>
    </div>
    </div>
   </body>
   ';
    }else{
        exit('


        <div class="formposition">
        <div class="container">
          <div class="row">
            <div class="col-md-offset-3 col-md-6">
              <form class="form-horizontal" action="enter.php" method="POST">
                <span class="heading"> <img src="/image/logo.png" class="logoimglogin">
                             <br>
                 АО НПЦ <br>Гипроздрав</span>
                <div class="form-group">
              <p><h3>Неверный пароль!</h3></p>
                </div>
                <div class="form-group">
                <input type="button" class="btn btn-default" value="Назад" onclick="history.back()">
            
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      ');
    }
}

}
echo "
<script type='text/javascript'>
function ToAuth() {
    location='index.php';
    }

    setTimeout('ToAuth()', 2500);
</script>

";
?>
