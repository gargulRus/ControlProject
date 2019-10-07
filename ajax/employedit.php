<?php
sleep(1);
echo'
<div class="showon">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Управлние БД</h1>
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
                <div class="list-group">
                    <a href="#" id="clients" class="list-group-item">
                        <i class="fa1 fa-user fa-fw"></i> Клиенты
                    </a>
                    <a href="#" id="executors" class="list-group-item">
                        <i class="fa1 fa-user fa-fw"></i> Исполнители
                    </a>
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

    $("#clients").bind("click", function(){
        var empltype ='clients'; 
        $.ajax ({
            type:"POST",
            url: "ajax/actions/empledit.php",
            data: {"empltype":empltype
                        },
            //dataType: "html",
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
    });
    $("#executors").bind("click", function(){
        var empltype ='executors'; 
        $.ajax ({
            type:"POST",
            url: "ajax/actions/empledit.php",
            data: {"empltype":empltype
                        },
            //dataType: "html",
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
    });
    
});
</script>