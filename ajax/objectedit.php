<?php
sleep(1);
echo'
<div class="showon">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Работа с Проектами</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-8">
                <div id="objecthere">
                    <p> Выберите действие</p>
                </div>
        </div>
        <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
              </i> Список действий
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="list-group">';
                    if($_COOKIE['role']=='admin'){
                        echo'
                        <a href="#" id="createobj" class="list-group-item">
                            <i class="fa1 fa-folder fa-fw"></i> Создать проект
                        </a>
                        <a href="#" id="editobj" class="list-group-item">
                            <i class="fa1 fa-folder fa-fw"></i> Изменить, удалить проект
                        </a>
                        ';
                    }else{}
                    echo '
                </div>
                <!-- /.list-group -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>
<!-- /.showon -->
';
?>

<script>

function funcBeforeObject () {
    $("#objecthere").text ("Ожидаю данные...");
}

function funcSuccessObject (data) {
    $("#objecthere").html(data);
}



$(document).ready(function(){

    $("#createobj").bind("click", function(){ 
        $.ajax ({
            url: "ajax/actions/createobject.php",
            dataType: "html",
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
    });

    $("#editobj").bind("click", function(){ 
        $.ajax ({
            url: "ajax/actions/editobject.php",
            dataType: "html",
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
    });
    $("#editsostav").bind("click", function(){ 
        $.ajax ({
            url: "ajax/actions/editsostav.php",
            dataType: "html",
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
    });
    
});
</script>