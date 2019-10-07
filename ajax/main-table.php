<?php
sleep(1);
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');

if(isset($_POST['action']) && $_POST['action']=='delete')
{   

   echo 'Перемещаю проект в корзину-'.$_POST['obname'].' ИД - '.$_POST['obId'];
        $objectId = $_POST['obId'];
       //Сохраняем инфу в базу объекта
       $result = query ("UPDATE mainproject SET 
        trash = 1
        WHERE id=".$objectId);

    echo'
    <script>
        function funcBefore () {
            $("#main-area").text ("Ожидаю данные...");
        }
        
        function funcSuccess (data) {
            $("#main-area").html(data);
        }
        function func() {
            $.ajax ({
                url: "/ajax/main-table.php",
                beforeSend: funcBefore,
                success: funcSuccess
            });
          }
           setTimeout(func, 2000);
        </script>
    ';
    exit;
}
elseif(isset($_POST['action']) && $_POST['action']=='update')
{       
    $objectId = $_POST['obId'];
    $service = $_POST['service'];
    $speed = $_POST['speed'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $date_plan = $_POST['date_plan'];
    $state_id = $_POST['state_id'];
    $client_id = $_POST['client_id'];
    $executor_id = $_POST['executor_id'];
    $username = $_COOKIE['name'];
        //проверим изменился ли исполнитель
        $checkExecutor=query("SELECT executor_id FROM mainproject WHERE id=".$objectId);
        for ($exarr=array(); $row=mysqli_fetch_assoc($checkExecutor) ;$exarr[]=$row);
        //если исполнитель изменился - отправляем новому исполнителю уведомление
        if($executor_id != $exarr[0]['executor_id']){
            $executor = getExecutorsList($executor_id);
            $client = getClientsList($client_id);

            //Отправляем сообщение
            $subject = 'Учет проектов';
            $message = 'Вам назначена задача. Клиент -'.$client[0]['name'].' Услуга - '.$service.'<br>';
            $message.= 'Статус - '.$status;
            goMail($executor[0]['email'], $subject, $message);

        }
        //Сохраняем инфу в базу объекта
        $result = query ("UPDATE mainproject SET 
            service = '$service' ,
            speed = '$speed' ,
            address = '$address' ,
            status = '$status' ,
            state_id = '$state_id' ,
            executor_id = '$executor_id' ,
            client_id = '$client_id' ,
            username = '$username' ,
            date_plan = '$date_plan'
        WHERE id=".$objectId);
    echo 'Обновляю проект';
    echo'
    <script>
        function funcBeforeObject () {
            $("#objecthere").text ("Ожидаю данные...");
        }
        function funcSuccessObject (data) {
            $("#objecthere").html(data);
        }
        function func() {
            $.ajax ({
                url: "/ajax/main-table.php",
                beforeSend: funcBefore,
                success: funcSuccess
            });
          }
           setTimeout(func, 2000);
        </script>
    ';
    exit;
}
elseif(isset($_POST['action']) && $_POST['action']=='create')
{   
    //Вся эта ебала для автоматического формирования уникального номера
    //спрашиваем базу - вытаскиваем самый последний порядковый номер        
    $result2 = query("SELECT name FROM mainproject ORDER BY name DESC");
    for ($objects=array(); $row=mysqli_fetch_assoc($result2) ;$objects[]=$row); 
    //var_dump($objects);
    //разбиваем дефисами
    $explo = explode('-', $objects[0]['name']);
    //прибавляем +1
    $newnum = $explo[2]+1;
    //поправляем нули перед числом
    $newnum = sprintf('%06d', $newnum);
    //готово к записи в базу
    $name = 'GA-'.date('Y').'/'.date('W').'-'.$newnum;
            $service = $_POST['service'];
            $speed = $_POST['speed'];
            $address = $_POST['address'];
            $status = $_POST['status'];
            $dateStart = $_POST['dateStart'];
            $stateId = $_POST['state'];
            $clientId = $_POST['client'];
            $executorId = $_POST['executor'];
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

            //Отправляем сообщение
            $subject = 'Учет проектов';
            $message = 'Вам поступила новая задача. Клиент -'.$client[0]['name'].' Услуга - '.$service;
            goMail($executor[0]['email'], $subject, $message);
            echo $executor[0]['email'];

            echo '<br>';
            echo 'Создаю Проект';
            echo'
            <script>
                function funcBeforeObject () {
                    $("#objecthere").text ("Ожидаю данные...");
                }
                function funcSuccessObject (data) {
                    $("#objecthere").html(data);
                }
                function func() {
                    $.ajax ({
                        url: "/ajax/main-table.php",
                        beforeSend: funcBefore,
                        success: funcSuccess
                    });
                  }
                   setTimeout(func, 2000);
                </script>
            ';
            exit;
}   
elseif(isset($_POST['action']) && $_POST['action']=='statusEdit')
{
    $objectId = $_POST['obId'];
    $statusedit = $_POST['statusedit'];
        //Сохраняем инфу в базу объекта
        $result = query ("UPDATE mainproject SET 
            status = '$statusedit'
        WHERE id=".$objectId);
        //отправляем собщение на почту

    echo 'Обновляю статус';
    echo'
    <script>
        function funcBeforeObject () {
            $("#objecthere").text ("Ожидаю данные...");
        }
        function funcSuccessObject (data) {
            $("#objecthere").html(data);
        }
        function func() {
            $.ajax ({
                url: "/ajax/main-table.php",
                beforeSend: funcBefore,
                success: funcSuccess
            });
          }
           setTimeout(func, 2000);
        </script>
    ';
    exit;

}
//Делаем запрос в таблицу c состояниями
$selectState = getStates();
$selectStateForPCreate = getStatesForPCreate();

//Делаем запрос в таблицу c исполнителями
$selectExec = getExecutors();
$selectExecForPCreate = getExecutorsForPCreate();

$selectClients = getClients();
$selectClientsForPCreate = getClientsForPCreate();
//Запрос в базу. Формируем массив для обработки
/*Тут после первого запроса, перебираем полученный массив с
объектами, и на кажду итерацию цикла делаем еще один запрос в таблицу с задачами,
где по id объекта ищем задачи относящиеся к данному объекту.
*/

// echo $_POST['filter'];
// echo $_POST['stateName'];
// echo $_POST['stateId'];
if(isset($_POST['stateId']))
{   
    if(isset($_POST['exeId']))
    {
        $objects = getAbiobjects($_POST['stateId'], $_POST['exeId']); 
    }
    else
    {
        $objects = getAbiobjects($_POST['stateId'], NULL);
    }
    
}
elseif(isset($_POST['exeId']))
{   
    $objects = getAbiobjects(NULL, $_POST['exeId']);
}
else
{
    $objects = getAbiobjects();
}


// var_dump($objects);
echo '
<section>
<div class="showon">
<div class="row">
<div class="col-lg-12">
    <h1 class="page-header">Сводная таблица Проектов</h1>
</div>
<!-- /.col-lg-12 -->
</div>
<div class="row upperButtons">
        <div class="col-lg-2">
        <br>';
        if($_COOKIE['role_id']=='1')
        {
            echo '
            <a class="pop-mod-create" href="#createProjectModal">
                <span id="" class="btn btn-sm btn-primary">
                    + Новый проект
                </span>
            </a>
            ';
        }

        echo'
        </div>
        <div class="col-lg-2">
        <label for="compsel">Состояние</label>
            '.$selectState.'
        </div>
        <div class="col-lg-2">
        <label for="compsel">Исполнитель</label>
            '.$selectExec.'
        </div>
        <div class="col-lg-6">
        <br>
        <button type="button" class="btn btn-primary btn-sm" id="filterBtn">
                Применить фильтр
            </button>
            <input type="hidden" id="stateName"  value="">
            <input type="hidden" id="stateId"  value="">
            <input type="hidden" id="exeName"  value="">
            <input type="hidden" id="exeId"  value="">
        </div>
</div>
<br>
<div id="table-here">';
echo'
<div class="tbl-header">
<table cellpadding="0" cellspacing="0" class="table-bordered ">
  <thead>
    <tr>
        <th>№</th>
        <th>Клиент</th>
        <th>Состояние</th>
        <th>Дата ввода</th>
        <th>Исполнитель</th>
        <th>Услуга</th>
        <th>Скорость</th>
        <th>Адрес</th>
        <th>Статус</th>
    </tr>
  </thead>
</table>
</div>
<div class="tbl-content">
<table cellpadding="0" cellspacing="0" class="table-bordered table-hover table-condensed">
  <tbody>';
  $datenow = strtotime(date("d.m.Y"));
  //проверяем даты для окрашивания ячеек
  foreach ($objects as $key =>$row)
  {     
      //проходимся по массиву ищием даты
      $datePlan = strtotime($row['date_plan']);
      $dat=0; //переменная для проверки
      $bgcol="";
      
      //Если дата просрочена а статус в РАБОТЕ - то ячейка красная
      if($datePlan < $datenow && $row['state_id']==1)
      {   
          $dat=1;
          $bgcol="#ff6e6e";
      }
      if($datePlan > $datenow && $row['state_id']==1)
      {   
          $dat=4;
          $bgcol="";
      }
      //Если дата не просрочена - ничего не делаем
      if($datePlan > $datenow)
      {
          $dat=2;
          $bgcol="";
      }
      //Если дата равна дате сегодняшней и статус в РАБОТЕ - ячейка желтая
      if($datePlan == $datenow && $row['state_id']==1) 
      {
          $dat=3;
          $bgcol="#fffa7d";
      }
    //   echo $datenow.' + '.$datePlan.' + '.$dat.' + '.$bgcol.'<br>';
      echo '
  <tr>
      <td>
        ';
        if($_COOKIE['role_id']=='1')
        {
            echo $row['name'].'         
            <a class="pop-mod-edit'.$row['id'].'" href="#editProjectModal_'.$row['id'].'">
                <span id="" class="btn btn-success">
                    <i class="fa1 fa-pencil"></i>
                </span>
            </a>
            <a class="pop-mod-del'.$row['id'].'" href="#deleteProjectModal_'.$row['id'].'">
                <span id="" class="btn btn-danger">
                    <i class="fa1 fa-trash-o"></i>
                </span>
            </a>';

        }
        else
        {
            echo $row['name'];
        }
        echo'
      </td>
      <td>
          <a class="pop-mod-client" href="#client">
          '.$row['client_name'].'
          </a>
      </td>
      <td bgcolor="'.$bgcol.'">
          <a href="#"  data-toggle="modal" data-target="#changeState">'.$row['state_name'].'</a>
      </td>
      <td>
          <a href="#"  data-toggle="modal" data-target="#changeDate">'.$row['date_plan'].'</a>
      </td>
       <td>
           <a href="#"  data-toggle="modal" data-target="#changeExecutor">'.$row['executor_name'].'</a>
       </td>
       <td>
          <a href="#"  data-toggle="modal" data-target="#changeService">'.$row['service'].'</a>
       </td>
       <td>
          <a href="#"  data-toggle="modal" data-target="#changeSpeed">'.$row['speed'].' Мб\с</a>
       </td>
       <td>
          <a href="#"  data-toggle="modal" data-target="#changeAddress">'.$row['address'].'</a>
       </td>';
       if($_COOKIE['role_id']==1 || $_COOKIE['exe_id']==$row['executor_id']){
        echo'
        <td>
        <a class="pop-mod-statEdit'.$row['id'].'" href="#editStatusModal_'.$row['id'].'">
        '.$row['status'].'
        </a>
        </td>
        ';
       }else{
        echo'
        <td>
           <a href="#"  data-toggle="modal" data-target="#">'.$row['status'].'</a>
        </td>
        ';
       }

       echo'
  </tr>
      ';
   
  }

    echo ' 
  </tbody>
</table>
</div>';
  echo'
</div><!-- tab-here -->
</div><!-- showon -->
</section>
';
?>

<div id="createProjectModal" class="mfp-hide white-popup-block">
    <h1>Новый проект</h1>
    <div class="modal-body">
    <form id="formId">
    <div class="col-sm-4">
        <br>
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
                <?php echo $selectStateForPCreate ;?>
            </div>
    </div>
    <div class="col-sm-4">
        <br>
            <div class="form-group">
                <label for="compsel">Клиент</label>
                <?php echo $selectClientsForPCreate ;?>
            </div>
            <div class="form-group">
                <label for="compsel">Исполнитель</label>
                <?php echo $selectExecForPCreate ;?>
            </div>
            <br>
    </div>
    </form>
    </div>
    <div class="modal-footer">
        <button id="saveBtn" class="btn btn-success popup-modal-create">Сохранить</button>
        <button id="closeBtn"class="btn btn-danger popup-modal-crdismiss">Закрыть</button>
    </div>
</div>

<!-- Модальное окно клиента -->
<div id="client" class="mfp-hide white-popup-block">
    <h1>Клиент</h1>
    <div class="modal-body">
        <div class="form-group">
           <p>Какая-то информация о клиенте(?)</p>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger pop-diss-client">Закрыть</button>
    </div>
</div>

<?php
foreach ($objects as $key =>$row)
  {
      //модальное окно для редактирования проекта
      echo '     
            <div id="editProjectModal_'.$row['id'].'" class="mfp-hide white-popup-block mfp-width">
                <h1>'.$row['name'].' - '.$row['client_name'].'</h1>
                <div class="modal-body">
                
                <form id="formId">
                    <div class ="col-sm-6">
                    <br>
                        <div class="form-group">
                            <label for="compsel">Услуга</label>
                            <input name="service'.$row['id'].'" type="text" value="'.$row['service'].'" placeholder="Услуга" id="service'.$row['id'].'" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="compsel">Скорость</label>
                            <input name="speed" type="text" value="'.$row['speed'].'" placeholder="Скорость" id="speed'.$row['id'].'" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="compsel">Адрес</label>
                            <input name="address" type="text" value="'.$row['address'].'" placeholder="Адрес" id="address'.$row['id'].'" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="compsel">Статус</label>
                            <input name="status" type="text" value="'.$row['status'].'" placeholder="Статус" id="status'.$row['id'].'" class="form-control">
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <br>
                            <label for="compsel">Изменить дату ввода</label>
                            <br>
                            <input type="date" placeholder="начало П" id="date_plan'.$row['id'].'" name="date_plan" value="'.$row['date_plan'].'">
                            <br>
                        </div>
                        <div class="form-group">
                            <label for="compsel">Состояние</label>
                            ';
                            $resob = query("SELECT state_id, state_name FROM `state`");
                            if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
                            if(mysqli_num_rows($resob)>0){
                                    echo'<select name="statesel'.$row['id'].'"  class="form-control input-sm" id="statesel'.$row['id'].'">';
                                    echo '<option value=""></option>';
                                    while($col = mysqli_fetch_assoc($resob)){
                                        echo '<option value='.$col['state_id'].' data-id="'.$col['state_id'].'" data-name="'.$col['state_name'].'">'.$col['state_name'].'</option>';
                                    }
                                    echo"</select>";
                                }
                            echo'
                        </div>
                        <div class="form-group">
                            <label for="compsel">Клиент</label>
                            ';
                            $resob = query("SELECT client_id, client_name FROM `client`");
                            if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
                            if(mysqli_num_rows($resob)>0){
                                    echo '<select name="clientSel'.$row['id'].'"  class="form-control input-sm" id="clientSel'.$row['id'].'">';
                                    echo '<option value=""></option>';
                                    while($col = mysqli_fetch_assoc($resob)){
                                        echo '<option value='.$col['client_id'].' data-id="'.$col['client_id'].'" data-name="'.$col['client_name'].'">'.$col['client_name'].'</option>';
                                    }
                                    echo "</select>";
                                }
                            echo'
                        </div>
                        <div class="form-group">
                            <label for="compsel">Исполнитель</label>
                            ';
                            $resob = query("SELECT executor_id, executor_name FROM `executor`");
                            if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
                            if(mysqli_num_rows($resob)>0){
                                echo '<select name="exeSel'.$row['id'].'"  class="form-control input-sm" id="exeSel'.$row['id'].'">';
                                echo '<option value=""></option>';
                                    while($col = mysqli_fetch_assoc($resob)){
                                        echo '<option value='.$col['executor_id'].' data-id="'.$col['executor_id'].'" data-name="'.$col['executor_name'].'">'.$col['executor_name'].'</option>';
                                    }
                                    echo  "</select>";
                                }
                            echo'
                        </div>
                        <br>
                    </div>
                </form>


                </div>
                <div class="modal-footer">
                    <button class="btn btn-success pop-edit'.$row['id'].'" id="editBtn_'.$row['id'].'">Сохранить</button>
                    <button class="btn btn-danger pop-edit-diss'.$row['id'].'">Закрыть</button>
                </div>
            </div>
        ';
              //модальное окно для удаления проекта
      echo '     
            <div id="deleteProjectModal_'.$row['id'].'" class="mfp-hide white-popup-block">
                <h1>'.$row['name'].' - '.$row['client_name'].'</h1>
                <div class="modal-body">
                        Удалить проект '.$row['name'].' ?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default pop-del'.$row['id'].'" id="deleteBtn_'.$row['id'].'">Удалить</button>
                    <button class="btn btn-danger pop-diss'.$row['id'].'">Закрыть</button>
                </div>
            </div>
        ';
              //модальное окно для редактирования статуса проекта
      echo '     
            <div id="editStatusModal_'.$row['id'].'" class="mfp-hide white-popup-block">
                <h1>'.$row['name'].' - '.$row['client_name'].'</h1>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="compsel">Статус</label>
                        <input name="statusedit" type="text" value="'.$row['status'].'" placeholder="Статус" id="statusedit'.$row['id'].'" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success pop-edit-status'.$row['id'].'" id="saveStatusBtn_'.$row['id'].'">Сохранить</button>
                    <button class="btn btn-danger pop-diss-status'.$row['id'].'">Закрыть</button>
                </div>
            </div>
        ';
  }
?>

<!-- Изменить проект -->
<div class="modal fade" id="editProject">
    <div class="modal-dialog modalAbi modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">%Номер проекта% - %клиент%</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Далее форма с данными из базы
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button id="testBtn" class="btn btn-success">Сохранить</button>
                <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            </div>

        </div>
    </div>
</div>
<!-- Удалить проект -->
<div class="modal fade" id="deleteProject">
    <div class="modal-dialog modalAbi modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">%Номер проекта% - %клиент%</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                Форма подтверждения удаления проекта
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Удалить</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            </div>

        </div>
    </div>
</div>
<!-- Исполнитель -->
<div class="modal fade" id="executorEdit">
    <div class="modal-dialog modalAbi modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Окно для исполнителя %Номер проекта% - %клиент%</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            Показывать окно только для исполнителя<br>
            Дать ему возможность поменять Статус.
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Сохранить</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
            </div>

        </div>
    </div>
</div>
<!-- Клиент -->
<div class="modal fade" id="changeClient">
    <div class="modal-dialog modalAbi modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Клиент</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            Возможно вывод более детализированной информации по клиенту
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">

                <button type="button" class="btn btn-danger" data-dismiss="modal">Отменить</button>
            </div>

        </div>
    </div>
</div>

<script>
// '.tbl-content' consumed little space for vertical scrollbar, 
//scrollbar width depend on browser/os/platfrom. Here calculate the scollbar width .
$(window).on("load resize ", function() {
  var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
  $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();

function funcBefore () {
    $("#main-area").text ("Ожидаю данные...");
}

function funcSuccess (data) {
    $("#main-area").html(data);
}



$(document).ready(function(){
  $('#statesel').change(function () {
        $("#stateName").val($(this).find(':selected').data('name'));
        $("#stateId").val($(this).find(':selected').data('id'));
  
    });

    $('#exeSel').change(function () {
        $("#exeName").val($(this).find(':selected').data('name'));
        $("#exeId").val($(this).find(':selected').data('id'));
  
    });


    $("#filterBtn").bind("click", function(){ 
        var stateName = document.getElementById("stateName").value;
        var stateId = document.getElementById("stateId").value;
        var exeName = document.getElementById("exeName").value;
        var exeId = document.getElementById("exeId").value;
        $.ajax ({
            type:"POST",
            url: "/ajax/main-table.php",
            data: {"stateName":stateName,"stateId":stateId, "exeName":exeName,"exeId":exeId },
           // dataType: "html",
            beforeSend: funcBefore,
            success: funcSuccess
        });
    });

    <?php

    //обработчик модальных окон для редактирования объекта
    foreach ($objects as $key =>$row)
    {   
        echo'
            $(".pop-mod-edit'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $("#clientSel'.$row['id'].'").val("'.$row['client_id'].'");
                $("#exeSel'.$row['id'].'").val("'.$row['executor_id'].'");
                $("#statesel'.$row['id'].'").val("'.$row['state_id'].'");
            });

            $(".pop-mod-edit'.$row['id'].'").magnificPopup({
                type: "inline",
                preloader: false,
                focus: "#username",
                modal: true
            });

            $(".pop-edit'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
                var action = "update";
                var obId = '.$row['id'].';
                var obname = "'.$row['name'].'";
                var service = document.getElementById("service'.$row['id'].'").value;
                var speed = document.getElementById("speed'.$row['id'].'").value;
                var address = document.getElementById("address'.$row['id'].'").value;
                var status = document.getElementById("status'.$row['id'].'").value;
                var date_plan = document.getElementById("date_plan'.$row['id'].'").value;
                var state_id = document.getElementById("statesel'.$row['id'].'").value;
                var client_id = document.getElementById("clientSel'.$row['id'].'").value;
                var executor_id = document.getElementById("exeSel'.$row['id'].'").value;
                $.ajax ({
                    type:"POST",
                    url: "/ajax/main-table.php",
                    data: {"action":action,
                            "obId":obId,
                            "obname":obname,
                            "service":service,
                            "speed":speed,
                            "address":address,
                            "status":status,
                            "date_plan":date_plan,
                            "state_id":state_id,
                            "client_id":client_id,
                            "executor_id":executor_id
                    },
                    dataType: "html",
                    beforeSend: funcBefore,
                    success: funcSuccess
                });
            });
            $(".pop-edit-diss'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
            });
        ';
    }
    //обработчик модальных окон для удаления объекта
    foreach ($objects as $key =>$row)
    {   
        echo'

            $(".pop-mod-del'.$row['id'].'").magnificPopup({
                type: "inline",
                preloader: false,
                focus: "#username",
                modal: true
            });

            $(".pop-del'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
                var action = "delete";
                var obId = '.$row['id'].';
                var obname = "'.$row['name'].'";
                console.log('.$row['id'].');
                console.log(action);
                $.ajax ({
                    type:"POST",
                    url: "/ajax/main-table.php",
                    data: {"action":action,
                            "obId":obId,
                            "obname":obname
                    },
                    dataType: "html",
                    beforeSend: funcBefore,
                    success: funcSuccess
                });
            });
            $(".pop-diss'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
            });
        ';
    }
    //обработчик модальных окон для редактирования статуса
    foreach ($objects as $key =>$row)
    {   
        echo'

            $(".pop-mod-statEdit'.$row['id'].'").magnificPopup({
                type: "inline",
                preloader: false,
                focus: "#username",
                modal: true
            });

            $(".pop-edit-status'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
                var action = "statusEdit";
                var obId = '.$row['id'].';
                var executor_id = '.$row['executor_id'].';
                var statusedit = document.getElementById("statusedit'.$row['id'].'").value;
                console.log('.$row['id'].');
                console.log(action);
                console.log(statusedit);
                $.ajax ({
                    type:"POST",
                    url: "/ajax/main-table.php",
                    data: {"action":action,
                            "obId":obId,
                            "executor_id":executor_id,
                            "statusedit":statusedit
                    },
                    dataType: "html",
                    beforeSend: funcBefore,
                    success: funcSuccess
                });
            });
            $(".pop-diss-status'.$row['id'].'").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
            });
        ';
    }
    ?>

            $(".pop-mod-create").magnificPopup({
                type: "inline",
                preloader: false,
                focus: "#username",
                modal: true
            });

            $(".popup-modal-create").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
                var action = "create";
                console.log(action);
                var service = document.getElementById("service").value;
                var speed = document.getElementById("speed").value;
                var address = document.getElementById("address").value;
                var status = document.getElementById("status").value;
                var dateStart = document.getElementById("dateStart").value;
                var state = document.getElementById("stateselPCreate").value;
                var client = document.getElementById("clientSelPCreate").value;
                var executor = document.getElementById("exeSelPCreate").value;
                $.ajax ({
                    type:"POST",
                    url: "/ajax/main-table.php",
                    data: {"action":action,
                            "service":service,
                            "speed":speed,
                            "address":address,
                            "status":status,
                            "dateStart":dateStart,
                            "state":state,
                            "client":client,
                            "executor":executor
                    },
                    dataType: "html",
                    beforeSend: funcBefore,
                    success: funcSuccess
                });
            });
            $(".popup-modal-crdismiss").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
            });

            $(".pop-mod-client").magnificPopup({
                type: "inline",
                preloader: false,
                focus: "#username",
                modal: true
            });

            $(".pop-diss-client").bind("click", function(e){ 
                e.preventDefault();
                $.magnificPopup.close();
            });


});

</script>
