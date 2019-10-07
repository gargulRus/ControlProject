<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');

if(isset($_POST['data']))
{
    parse_str($_POST['data'],$output);
    sleep(1);

    //вытаскиваем значения из массива.
    $objectId = $output['obsel'];
    $nameNew = $output['namenew'];
    $service=$output['service'];
    $speed=$output['speed'];
    $address=$output['address'];
    $status=$output['status'];
    $date_plan=$output['date_plan'];
    $clientSel=$output['clientSel'];
    $exeSel=$output['exeSel'];
    $statesel=$output['statesel'];

    //Сохраняем инфу в базу объекта
    $result = query ("UPDATE mainproject SET 
        name = '$nameNew' ,
        service = '$service' ,
        speed = '$speed' ,
        address = '$address' ,
        status = '$status' ,
        state_id = '$statesel' ,
        executor_id = '$exeSel' ,
        client_id = '$clientSel' ,
        date_plan = '$date_plan'
    WHERE id=".$objectId);
    
    //Запрашиваем в базе почту исполнителя
    $executor = getExecutorsList($exeSel);

    //Запрашиваем в базе имя клиента
    $client = getClientsList($clientSel);

    //Отправляем сообщение
    goMail($executor[0]['email'], $client[0]['name'], $service);
    //Перегружаем скрипт
    echo'
    <h4>Вношу изменения в проект!</h4>
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
                url: "ajax/actions/editobject.php",
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
elseif(isset($_POST['deleteOb']))
{
    sleep(1);
    $objectId = $_POST['objectId'];
    $result= query("DELETE FROM mainproject WHERE id=".$objectId);
    echo'
    <h4>Удаляю проект!</h4>
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
                url: "ajax/actions/editobject.php",
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
$selectob = getObjectsForEdit();
$selectState = getStates();
$selectExec = getExecutors();
$selectClients = getClients();
echo'
<div class="showon">
<form id="formId">
    <div class="col-sm-4">
            <label for="obsel">Выберите проект</label>
            '.$selectob.'
            <br>
            <br>
            <div class="form-group">
                <label for="obsel">Переименовать проект</label>
                <br>
                <input type="text" placeholder="Новое название" id="namenew" name="namenew" class="form-control">            
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
            <label for="compsel">Изменить дату ввода</label>
            <br>
            <input type="date" placeholder="начало П" id="date_plan" name="date_plan" value="">
            <br>
        </div>
        <div class="form-group">
            <label for="compsel">Состояние</label>
            '.$selectState.'
        </div>
    </div>
    <div class="col-sm-4">
    <label for="compsel">Смена Сотрудников</label>
        <div class="form-group">
            <label for="compsel">Клиент</label>
            '.$selectClients.'
        </div>
        <div class="form-group">
            <label for="compsel">Исполнитель</label>
            '.$selectExec.'
        </div>
        <br>
    </div>
    <div class="col-sm-8">
    </div>
    <div class="col-sm-4">
    <br>
    </div>
    <div class="col-sm-4">
    <br>
    </div>
    <div class="col-sm-4">
    <br>
    </div>
    <div class="col-sm-4">
    <div class="form-group text-right">
    <input type="hidden" id="objectId"  value="NULL">
    <span type="submit" id="delete" class="btn btn-danger btn-sm">Удалить Проект</span>
    <span type="submit" id="update" class="btn btn-success btn-sm">Сохранить изменения</span>
    </div>
    </div>
    </form>
</div>
';
}
?>

<script>
$( document ).ready(function() {
    //Берем значения из выпадающего списка с объектами
    //и подставляем в форму
    $('#obsel').change(function () {
        $("#namenew").val($(this).find(':selected').data('name'));
        $("#service").val($(this).find(':selected').data('service'));
        $("#speed").val($(this).find(':selected').data('speed'));
        $("#address").val($(this).find(':selected').data('address'));
        $("#status").val($(this).find(':selected').data('status'));
        $("#date_plan").val($(this).find(':selected').data('date_plan'));
        $("#clientSel").val($(this).find(':selected').data('client_id'));
        $("#exeSel").val($(this).find(':selected').data('executor_id'));
        $("#statesel").val($(this).find(':selected').data('state_id'));
        $("#objectId").val($(this).find(':selected').data('id'));
    });

    //Смотрим в форму, получаем значения, сериализуем и отправляем массив в обработку
    $('#update').click(function(){
        const result = $('form').serialize();
        var data = result;
        console.log(data);
            $.ajax ({
            type:"POST",
            url: "ajax/actions/editobject.php",
            data: {"data":data
                     },
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
        return false; 
    });
    //Кнопка удаления
    $('#delete').click(function(){
        var objectId = document.getElementById("objectId").value;
        var deleteOb = 1;
            $.ajax ({
            type:"POST",
            url: "ajax/actions/editobject.php",
            data: {"deleteOb":deleteOb, "objectId":objectId
                     },
            beforeSend: funcBeforeObject,
            success: funcSuccessObject
        });
        return false; 
    });

});
</script>