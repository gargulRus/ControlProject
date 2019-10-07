<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');

if(isset($_POST['data']))
{
    parse_str($_POST['data'],$output);
    sleep(1);
    //вытаскиваем значения из массива.
    $objectId = $output['idob'];
    $nameNew = $output['namenew'];
    $pStart=$output['pStart'];
    $pEnd=$output['pEnd'];
    $expStart=$output['expStart'];
    $expEnd=$output['expEnd'];
    $rStart=$output['rStart'];
    $rEnd=$output['rEnd'];
    $idgip=$output['idgp'];
    $idgap=$output['idgap'];
    $idov=$output['idov'];
    $idkr=$output['idkr'];
    $idcur=$output['idcur'];
    $idexp=$output['idexp'];

    //Сохраняем инфу в базу объекта
    $result = query ("UPDATE objects SET 
    name = '$nameNew' ,
    gip_id = '$idgip' ,
    gap_id = '$idgap' ,
    ov_id = '$idov' ,
    kr_id = '$idkr' ,
    cur_id = '$idcur' ,
    exp_id = '$idexp'
    WHERE id='$objectId'");
    //Сохраняем обновленные даты в базу
    //ДАТЫ СТАДИИ П   
    if(isset($output['pStart']) && strlen($output['pStart'])>1)
    {   
        $pStart=$output['pStart'];
        $result = query ("UPDATE jobplan SET 
        data = '$pStart'
        WHERE object_id='$objectId' AND pos_num=1");
    }
    else
    {   
        $result = query ("UPDATE jobplan SET 
        data = NULL
        WHERE object_id='$objectId' AND pos_num=1");
    }

    if(isset($output['pEnd']) && strlen($output['pEnd'])>1)
    {   
        $pEnd=$output['pEnd'];
        $result = query ("UPDATE jobplan SET 
        data = '$pEnd'
        WHERE object_id='$objectId' AND pos_num=2");
    }
    else
    {
        $result = query ("UPDATE jobplan SET 
        data = NULL
        WHERE object_id='$objectId' AND pos_num=2");
    }
    //ДАТЫ Экспертизы   
    if(isset($output['expStart']) && strlen($output['expStart'])>1)
    {   
        $expStart=$output['expStart'];
        $result = query ("UPDATE jobplan SET 
        data = '$expStart'
        WHERE object_id='$objectId' AND pos_num=3");
    }
    else
    {
        $result = query ("UPDATE jobplan SET 
        data = NULL
        WHERE object_id='$objectId' AND pos_num=3");
    }

    if(isset($output['expEnd']) && strlen($output['expEnd'])>1)
    {   
        $expEnd=$output['expEnd'];
        $result = query ("UPDATE jobplan SET 
        data = '$expEnd'
        WHERE object_id='$objectId' AND pos_num=4");
    }
    else
    {
        $result = query ("UPDATE jobplan SET 
        data = NULL
        WHERE object_id='$objectId' AND pos_num=4");
    }
    //ДАТЫ СТАДИИ Р   
    if(isset($output['rStart']) && strlen($output['rStart'])>1)
    {   
        $rStart=$output['rStart'];
        $result = query ("UPDATE jobplan SET 
        data = '$rStart'
        WHERE object_id='$objectId' AND pos_num=5");
    }
    else
    {
        $result = query ("UPDATE jobplan SET 
        data = NULL
        WHERE object_id='$objectId' AND pos_num=5");
    }
    if(isset($output['rEnd']) && strlen($output['rEnd'])>1)
    {   
        $rEnd=$output['rEnd'];
        $result = query ("UPDATE jobplan SET 
        data = '$rEnd'
        WHERE object_id='$objectId' AND pos_num=6");
    }
    else
    {
        $result = query ("UPDATE jobplan SET 
        data = NULL
        WHERE object_id='$objectId' AND pos_num=6");
    }
    
    echo'
    <h4>Вношу изменения в объект!</h4>
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
                url: "ajax/actions/editsostav.php",
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

echo'
<div class="showon">
<form id="formId">
    <div class="col-sm-4">
            <label for="obsel">Выберите объект</label>
            '.$selectob.'
            <br>
    </div>
    <div class="col-sm-8" id="sostavhere">
        <div class="form-group ">
            <br>
            <br>
        </div>
    </div>
    <div class="col-sm-8">
    <br>
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
    <input type="hidden" id="objectId" value="NULL" />
    <input type="hidden" id="name" value="NULL" />
    </div>
    </div>
    </form>
</div>
';
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


    $('#obsel').change(function () {
        $("#objectId").val($(this).find(':selected').data('id'));
        $("#name").val($(this).find(':selected').data('name'));
        var objectId = document.getElementById("objectId").value;
        var name = document.getElementById("name").value;
        $.ajax ({
            type:"POST",
            url: "ajax/actions/sostav.php",
            data: {
                    "objectId":objectId,
                    "name":name,
                    },
            //dataType: "html",
            beforeSend: funcBeforeSostav,
            success: funcSuccessSostav
        });
    });

    // $("#obsel").bind("change", function(){ 
    //     $.ajax ({
    //         url: "ajax/objectedit/sostav.php",
    //         dataType: "html",
    //         beforeSend: funcBeforeSostav,
    //         success: funcSuccessSostav
    //     });
    // });
    //Смотрим в форму, получаем значения, сериализуем и отправляем массив в обработку
    // $('#update').click(function(){
    //     const result = $('form').serialize();
    //     var data = result;
    //     console.log(data);
    //         $.ajax ({
    //         type:"POST",
    //         url: "ajax/objectedit/editsostav.php",
    //         data: {"data":data
    //                  },
    //         beforeSend: funcBeforeObject,
    //         success: funcSuccessObject
    //     });
    //     return false; 
    // });

});
</script>