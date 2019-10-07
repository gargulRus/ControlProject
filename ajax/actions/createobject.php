<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');
//проверяем есть ли данные с формы
if(isset($_POST['data']))
{       
    sleep(1);
        parse_str($_POST['data'],$output);
        sleep(1);
        //имя объекта
        $name=$output['name'];
        //идентификаторы
        $stateId=$output['statesel'];
        $clientId=$output['clientSel'];
        $executorId=$output['exeSel'];
        //ДопИнформация по проекту
        $service=$output['service'];
        $speed=$output['speed'];
        $address=$output['address'];
        $status=$output['status'];
        $dateStart=$output['dateStart'];

        //Сохраняем в базу объект
        $result = query ("INSERT INTO `mainproject` ( `name`, `service`,
        `speed` , `address`, `status`, `state_id`, `executor_id`, `client_id`, `date_plan`) VALUES (
        '".$name."' , '".$service."',
        '".$speed."','".$address."',
        '".$status."',".$stateId.",
        ".$executorId.",".$clientId.",
        '".$dateStart."')");

        //Запрашиваем в базе почту исполнителя
        $executor = getExecutorsList($executorId);

        //Запрашиваем в базе имя клиента
        $client = getClientsList($clientId);

        goMail($executor[0]['email'], $client[0]['name'], $service);

        //перегружающий скрипт
        echo'
        <h4>Сохраняю проект!</h4>
        <script>
            function funcBeforeObject () {
                $("#objecthere").text ("Успешно!");
            }
    
            function funcSuccessObject (data) {
                $("#objecthere").html(data);
            }
    
            function func() {
                $.ajax ({
                    type:"POST",
                    url: "ajax/actions/createobject.php",
                    //dataType: "html",
                    beforeSend: funcBeforeObject,
                    success: funcSuccessObject
                });
              }
              
               setTimeout(func, 2000);
            </script>
        ';
        exit;
}
else{
    sleep(1);
    $selectState = getStates();
    $selectExec = getExecutors();
    $selectClients = getClients();
echo '
<div class="showon">
<form id="formId">
    <div class="col-sm-4">
       
            <div class="form-group">
                <label for="compsel">Название проекта</label>
                <input name="name" type="text" value="" placeholder="Название проекта" id="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="compsel">Услуга</label>
                <input name="service" type="text" value="" placeholder="Услуга" id="service" class="form-control">
            </div>
            <div class="form-group">
                <label for="compsel">Скорость</label>
                <input name="speed" type="text" value="" placeholder="Скорость" id="speed" class="form-control">
            </div>
            <div class="form-group">
                <label for="compsel">Адрес</label>
                <input name="address" type="text" value="" placeholder="Адрес" id="address" class="form-control">
            </div>
            <div class="form-group">
                <label for="compsel">Статус</label>
                <input name="status" type="text" value="" placeholder="Статус" id="status" class="form-control">
            </div>
            <br>
    </div>
    <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label for="compsel">Дата Ввода</label>
                <input type="date" placeholder="начало П" id="dateStart" name="dateStart">
                <br>
            </div>
            <div class="form-group">
                <label for="compsel">Состояние</label>
                '.$selectState.'
            </div>
    </div>
    <div class="col-sm-4">
            <div class="form-group">
                <label for="compsel">Клиент</label>
                '.$selectClients.'
            </div>
            <div class="form-group">
                <label for="compsel">Исполнитель</label>
                '.$selectExec.'
            </div>
            <br>
            <div class="form-group text-right">
                <input type="hidden" id="StateId"  value="NULL">
                <input type="hidden" id="ClientId"  value="NULL">
                <input type="hidden" id="ExecutorId"  value="NULL">
                <input type="submit" class="btn btn-success btn-sm" id="createObject" value="Создать Проект"/>
            </div>
    </div>
    </form>
</div>
';
}
?>

<script>
function funcBeforeObject () {
    $("#objecthere").text ("Ожидаю данные...");
}

function funcSuccessObject (data) {
    $("#objecthere").html(data);
}

$(document).ready(function(){

    //скрипты для получения id сотрудников
    $('#statesel').change(function () {
        $("#StateId").val($(this).find(':selected').data('id'));
    });
    $('#clientSel').change(function () {
        $("#ClientId").val($(this).find(':selected').data('id'));
    });
    $('#exeSel').change(function () {
        $("#ExecutorId").val($(this).find(':selected').data('id'));
    });
    //Что бы передать массив с неизвестным количеством Томов - сереализуем всю форму
    //и передаем ее в одной переменной
    $('form').on('submit', (e) => {
        e.preventDefault();
        const result = $('form').serialize();
        var name = document.getElementById("name").value;
        var clientId = document.getElementById("clientSel").value;
        var executorId = document.getElementById("exeSel").value;
        var data = result;
        console.log(data);

        if(name == "" )
        {
            alert("Укажите название Объекта!");
            return false;
        }
        else if(clientId == "")
        {
            alert("Выберите или создайте нового клиента!");
            return false;
        }
        else if(executorId == "")
        {
            alert("Выберите или создайте нового испонителя!");
            return false;
        }
        else
        {
            $.ajax ({
                type:"POST",
                url: "ajax/actions/createobject.php",
                data: {"data":data
                        },
                //dataType: "html",
                beforeSend: funcBeforeObject,
                success: funcSuccessObject
            });
        }
        return false;
    });
});
</script>