<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Учет Проектов</title>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="../css/table.css">
  <link rel="stylesheet" href="/core/plugins/magnificPopup/magnific-popup.css">
    <?php
        require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
    ?>
</head>
  <body>
  <!-- <div class="area"></div> -->
    <div id="main-area" class="main-area">
    Добро пожаловать!
    </div>
    <nav class="main-menu">
            <ul>
                <li>
                    <a href="#" id="loadMain">
                        <i class="fa fa-home fa-2x"></i>
                        <span class="nav-text">
                            Главная
                        </span>
                    </a>
                  
                </li>
                <li class="has-subnav">
                    <a href="#" id="loadMainTable">
                        <i class="fa fa-bar-chart-o fa-2x"></i>
                        <span class="nav-text">
                            Общая информация
                        </span>
                    </a>
                    
                </li>
                <!-- <li class="has-subnav">
                            <a href="#" id="loadObjectEdit">
                            <i class="fa fa-list fa-2x"></i>
                                <span class="nav-text">
                                    Работа с проектами
                                </span>
                            </a>
                </li> -->
                    <?php
                    if($_COOKIE['role_id']=='1')
                    {
                        echo '
                        <li class="has-subnav">
                            <a href="#" id="loadEmployEdit">
                            <i class="fa fa-user fa-2x"></i>
                                <span class="nav-text">
                                    Управление БД
                                </span>
                            </a>
                        </li>
                        ';
                    }else{}
                    ?>
                <li>
                    <a href="#" id="#">
                        <i class="fa fa-folder-open fa-2x"></i>
                        <span class="nav-text">
                            Проекты(?)
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" id="#">
                        <i class="fa fa-folder-open fa-2x"></i>
                        <span class="nav-text">
                            Доп. меню(?)
                        </span>
                    </a>
                </li>
            </ul>

            <ul class="logout">
                <li>
                   <a href="../exit.php">
                         <i class="fa fa-power-off fa-2x"></i>
                        <span class="nav-text">
                            Выход
                        </span>
                    </a>
                </li>  
            </ul>
        </nav>
  </body>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="/core/plugins/magnificPopup/jquery.magnific-popup.js"></script>
  <script>

function funcBefore () {
    $("#main-area").text ("Ожидаю данные...");
}

function funcSuccess (data) {
    $("#main-area").html(data);
}

$(document).ready(function(){
    $("#loadMain").bind("click", function(){ 
        $.ajax ({
            url: "/ajax/main.php",
            dataType: "html",
            beforeSend: funcBefore,
            success: funcSuccess
        });
    });
    
    $("#loadMainTable").bind("click", function(){ 
        $.ajax ({
            url: "/ajax/main-table.php",
            dataType: "html",
            beforeSend: funcBefore,
            success: funcSuccess
        });
    });
    $("#loadObjectEdit").bind("click", function(){ 
        $.ajax ({
            url: "/ajax/objectedit.php",
            dataType: "html",
            beforeSend: funcBefore,
            success: funcSuccess
        });
    });
    $("#loadEmployEdit").bind("click", function(){ 
        $.ajax ({
            url: "/ajax/employedit.php",
            dataType: "html",
            beforeSend: funcBefore,
            success: funcSuccess
        });
    });
    $("#loadObjectsExplore").bind("click", function(){ 
        $.ajax ({
            url: "/ajax/objectexplore.php",
            dataType: "html",
            beforeSend: funcBefore,
            success: funcSuccess
        });
    });
});
</script>


    </html>