<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');
sleep(1);
if(isset($_POST['data']))
{
    parse_str($_POST['data'],$output);
    $name=$output['name'];
    $object_id=$output['objectId'];
    echo $name.'<br>';
    echo $object_id.'<br>';
    foreach ($output['folder_name'] as $key => $row) {
        $tom_dir_name = $output['folder_name'][$key];
        $tom_dir_basepath='/files/'.$name.'/Проект/'.$tom_dir_name;
        $tom_dir_path = $_SERVER['DOCUMENT_ROOT'].'/files/'.$name.'/Проект/'.$tom_dir_name;
        if(!is_dir($tom_dir_path))
        {
            mkdir($tom_dir_path, 0777, true);
        } 
        //так же делаем запись в базу данных. Храним там путь
        $result = query("INSERT INTO `sostavproject` (`object_id`, `tomname`, `tom_path`) VALUES (
            ".$object_id.", '".$tom_dir_name."', '".$tom_dir_basepath."'
        )"); 
    }
    echo'
    <script>
    function funcBeforeSostav () {
        $("#sostavhere").text ("Ожидаю данные...");
    }

    function funcSuccessSostav (data) {
        $("#sostavhere").html(data);
    }
    
    var objectId = '.$object_id.';
    var name = "'.$name.'";
    function func1() {
        $.ajax ({
            type:"POST",
            url: "ajax/actions/sostav.php",
            data: {
                "objectId":objectId,
                "name":name,
                },
            beforeSend: funcBeforeSostav,
            success: funcSuccessSostav
        });
      }
      
       setTimeout(func1, 2000);
    </script>
';
exit;


}
elseif(isset($_POST['action']) && $_POST['action']=='update')
{
    $name = $_POST['name'];
    $tomname = $_POST['tomname'];
    $id =  $_POST['id'];
    $result = query("UPDATE sostavproject SET
    tomname = '$tomname'
    WHERE id = '$id'
    ");
    echo'
        <h4>Переименовываю Том</h4>
        <script>
            function funcBeforeSostav () {
                $("#sostavhere").text ("Ожидаю данные...");
            }
        
            function funcSuccessSostav (data) {
                $("#sostavhere").html(data);
            }
            
            var objectId = '.$_POST['objectId'].';
            var name = "'.$name.'";
            function func() {
                $.ajax ({
                    type:"POST",
                    url: "ajax/actions/sostav.php",
                    data:{
                        "objectId":objectId,
                        "name":name,
                        },
                    //dataType: "html",
                    beforeSend: funcBeforeSostav,
                    success: funcSuccessSostav
                });
              }
              
               setTimeout(func, 2000);
            </script>
        ';
        exit;
}
elseif(isset($_POST['action']) && $_POST['action']=='delete')
{   
    $result = query("DELETE FROM sostavproject WHERE id=".$_POST['id']);
    echo 'удаляем';
    echo $_POST['id'];
    echo'
        <script>
            function funcBeforeSostav () {
                $("#sostavhere").text ("Ожидаю данные...");
            }
        
            function funcSuccessSostav (data) {
                $("#sostavhere").html(data);
            }
            
            var objectId = '.$_POST['objectId'].';
            var name = "'.$_POST['name'].'";
            function func() {
                $.ajax ({
                    type:"POST",
                    url: "ajax/actions/sostav.php",
                    data:{
                        "objectId":objectId,
                        "name":name,
                        },
                    beforeSend: funcBeforeSostav,
                    success: funcSuccessSostav
                });
              }
              
               setTimeout(func, 2000);
            </script>
        ';
        exit;
}
else
{

$list = getObjectSostav($_POST['objectId']);

echo '
<div class="showon">';
// var_export($list);
echo'<br>';
  
    foreach ($list as $key => $row) {
        echo'
        <div class="form-group">

        <input type="text" class="input-sm" size="60" id="tom'.$row['id'].'"  value="'.$row['tomname'].'"/>

            <span type="submit" id="update'.$row['id'].'" class="btn btn-success">
                <i class="fa1 fa-pencil" aria-hidden="true"></i>
            </span>

            <span type="submit" id="delete'.$row['id'].'" class="btn btn-danger">
                <i class="fa1 fa-trash-o"></i>
            </span>
        </div>
        ';
    }
        echo'
            <input type="hidden" id="objectId" value="'.$_POST['objectId'].'" />
        ';
    echo'
    <form id="formedit">
        <div class="form-group copy">
            <input type="text" name="folder_name[]" size="60" id="folder_name" class="input-sm" placeholder="Наим. тома">
        </div>
        <div class="form-group">
        <input type="hidden" name="objectId" id="objectId"  value="'.$_POST['objectId'].'">
        <input type="hidden" name="name" id="name"  value="'.$_POST['name'].'">
        <button class="btn btn-warning btn-sm" id="copyBtn"><i class="fa1 fa-plus" aria-hidden="true"></i>Новый раздел</button>
        <input type="submit" class="btn btn-success btn-sm" id="editTom" value="Добавить в состав тома"/>
        </div>
    </form>
    ';
echo'</div>';
}
?>

<script>
    function funcBeforeSostav () {
        $("#sostavhere").text ("Ожидаю данные...");
    }

    function funcSuccessSostav (data) {
        $("#sostavhere").html(data);
    }

$( document ).ready(function() {

        //кнопка копирования строк для Томов
        $('#copyBtn').click(function(){
            $('.copy').append( $('#folder_name').eq(0).clone().val('') );
            return false;
        });


    <?php
    foreach ($list as $key => $row) {
        //кнопка для редактирования
        echo'
        $("#update'.$row['id'].'").click(function(){
            var objectId = document.getElementById("objectId").value;
            var tomname = document.getElementById("tom'.$row['id'].'").value;
            var id = '.$row['id'].';
            var name = "'.$_POST['name'].'";
            var action = "update";
            $.ajax ({
                type:"POST",
                url: "ajax/actions/sostav.php",
                data: {
                        "objectId":objectId,
                        "name":name,
                        "tomname":tomname,
                        "id":id,
                        "action":action
                        },
                //dataType: "html",
                beforeSend: funcBeforeSostav,
                success: funcSuccessSostav
            });
        });
        ';
        //кнопка для удаления 
        echo'
        $("#delete'.$row['id'].'").click(function(){
            var objectId = document.getElementById("objectId").value;
            var id = '.$row['id'].';
            var name = "'.$_POST['name'].'";
            var action = "delete";
            $.ajax ({
                type:"POST",
                url: "ajax/actions/sostav.php",
                data: {
                        "objectId":objectId,
                        "id":id,
                        "name":name,
                        "action":action
                        },
                //dataType: "html",
                beforeSend: funcBeforeSostav,
                success: funcSuccessSostav
            });
        });
        ';
        
    }

    ?>
        //Что бы передать массив с неизвестным количеством Томов - сереализуем всю форму
    //и передаем ее в одной переменной
    $('#formedit').on('submit', (e) => {
        e.preventDefault();
        const result = $('#formedit').serialize();
        var data = result;
        console.log(data);

            $.ajax ({
                type:"POST",
                url: "ajax/actions/sostav.php",
                data: {"data":data
                        },
                beforeSend: funcBeforeSostav,
                success: funcSuccessSostav
            });
        return false;
    });

    //  $('#update').click(function(){
    //     var objectId = document.getElementById("objectId").value;
    //     $.ajax ({
    //         type:"POST",
    //         url: "ajax/objectedit/sostav.php",
    //         data:"objectId=" + objectId,
    //         //dataType: "html",
    //         beforeSend: funcBeforeSostav,
    //         success: funcSuccessSostav
    //     });
    // });
});
</script>