<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/functions/functions.php');
sleep(1);
    //создаем новую запись в базу
if(isset($_POST['data']))
{

    parse_str($_POST['data'],$output);

        $name= $output['empl_name'];

            if(isset($output['empl_email'])) {
                $email=$output['empl_email'];
                $login=$output['empl_login'];
                $pass=$output['empl_pass'];
                $role_id=$output['roleSelect'];
                $toSql = add_employer($name, $_POST['empltype'], $email,$login, $pass,$role_id);
            }
            else
            {
                $toSql = add_employer($name, $_POST['empltype']);
            }

    echo'
    <h4>'.$toSql.'</h4>
    <script>
        function funcBeforeObject () {
            $("#objecthere").text ("Ожидаю данные...");
        }
        function funcSuccessObject (data) {
            $("#objecthere").html(data);
        }
        function func() {
            var empltype = "'.$_POST['empltype'].'";
            $.ajax ({
                type:"POST",
                url: "ajax/actions/empledit.php",
                data: {"empltype":empltype
                            },
                beforeSend: funcBeforeObject,
                success: funcSuccessObject
            });
          }
           setTimeout(func, 2000);
        </script>
    ';
    exit;
}
    //обновляем запись в базе
elseif(isset($_POST['action']) && $_POST['action']=='update')
{   

    if($_POST['empltype']=='executors')
    {
        $toSql = update_employer($_POST['id'],
         $_POST['name'], $_POST['empltype'], $_POST['email'], $_POST['login'], $_POST['pass']
         , $_POST['role_id']);
    }
    else{
        $toSql = update_employer($_POST['id'], $_POST['name'], $_POST['empltype']);
    }

        echo'
        <h4>'.$toSql.'</h4>
        <script>
            function funcBeforeObject () {
                $("#objecthere").text ("Ожидаю данные...");
            }
            
            function funcSuccessObject (data) {
                $("#objecthere").html(data);
            }
            

            function func() {
                var empltype = "'.$_POST['empltype'].'";
                $.ajax ({
                    type:"POST",
                    url: "ajax/actions/empledit.php",
                    data: {"empltype":empltype
                                },
                    beforeSend: funcBeforeObject,
                    success: funcSuccessObject
                });
              }
              
               setTimeout(func, 2000);
            </script>
        ';
        exit;
}
elseif(isset($_POST['action']) && $_POST['action']=='delete')
{
    $toSql = delete_employer($_POST['id'], $_POST['empltype']);
    echo'
    <h4>'.$toSql.'</h4>
    <script>
        function funcBeforeObject () {
            $("#objecthere").text ("Ожидаю данные...");
        }
        function funcSuccessObject (data) {
            $("#objecthere").html(data);
        }
        function func() {
            var empltype = "'.$_POST['empltype'].'";
            $.ajax ({
                type:"POST",
                url: "ajax/actions/empledit.php",
                data: {"empltype":empltype
                            },
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
switch ($_POST['empltype']) {
    case 'clients':
    $list = getClientsList();echo '<div class="showon"><h4>Клиенты</h4><br>';break;
    case 'executors':
    $list = getExecutorsList();echo '<div class="showon"><h4>Исполнители</h4><br>';break;
    default:
    echo '<div class="showon"><h4>Ошибка идентификатора!</h4><br>';break;
}

foreach ($list as $key => $row) {
    echo'
    <div class="form-group">
        <label for="employer'.$row['id'].'">Имя</label>
        <input type="text" class="input-sm" size="20" id="employer'.$row['id'].'"  value="'.$row['name'].'"/>
        ';
        if($_POST['empltype']=='executors')
        {
            echo '
            <label for="emplLogin'.$row['id'].'">Логин</label>
            <input type="text" class="input-sm" size="20" id="emplLogin'.$row['id'].'"  value="'.$row['login'].'"/>
            <label for="emplPass'.$row['id'].'">Пароль</label>
            <input type="text" class="input-sm" size="20" id="emplPass'.$row['id'].'"  value="'.$row['pass'].'"/>
            <label for="emplEmail'.$row['id'].'">E-mail</label>
            <input type="text" class="input-sm" size="20" id="emplEmail'.$row['id'].'"  value="'.$row['email'].'"/>
            <label for="emplEmail'.$row['id'].'">Роль</label>
            ';
            $resob = query("SELECT role_id, role_name FROM `role`");
            if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
            if(mysqli_num_rows($resob)>0){
                    echo '<select name="roleSelect'.$row['id'].'"  class="input-sm roleSelect" id="roleSelect'.$row['id'].'">';
                    echo  '<option value=""></option>';
                    while($col = mysqli_fetch_assoc($resob)){
                        echo  '<option value='.$col['role_id'].' data-id="'.$col['role_id'].'" data-name="'.$col['role_name'].'">'.$col['role_name'].'</option>';
                    }
                    echo "</select>";
                }
        }
        echo'
        <span type="submit" id="empl_update'.$row['id'].'" class="btn btn-success">
            <i class="fa1 fa-pencil" aria-hidden="true"></i>
        </span>
        <span type="submit" id="empl_delete'.$row['id'].'" class="btn btn-danger">
            <i class="fa1 fa-trash-o"></i>
        </span>
        <input type="hidden" id="empl_id'.$row['id'].'" value="'.$row['id'].'" />
    </div>
    <hr>
    ';
}
echo'
    <form id="formedit">
        <div class="form-group copy">
        <label for="empl_name">Имя</label>
            <input type="text" name="empl_name" size="20" id="empl_name" class="input-sm" placeholder="">
            ';

            if($_POST['empltype']=='executors')
            {
                echo'
                <label for="empl_login" class="mrg-left">Логин</label>
                <input type="text" name="empl_login" size="20" id="empl_login" class="input-sm" placeholder=""><br>
                <label for="empl_pass" class="mrg-left">Пароль </label>
                <input type="text" name="empl_pass" size="20" id="empl_pass" class="input-sm" placeholder=""><br>
                <label for="empl_email" class="mrg-left">E-mail</label>
                <input type="text" name="empl_email" size="20" id="empl_email" class="input-sm" placeholder=""><br>
                <label for="roleSelect" class="mrg-left">Роль</label>
                ';
                $resob2 = query("SELECT role_id, role_name FROM `role`");
                if(!$resob2) exit("Ошибка запроса: ".mysqli_error($resob2));
                if(mysqli_num_rows($resob2)>0){
                        echo '<select name="roleSelect"  class="input-sm roleSelect" id="roleSelect">';
                        echo  '<option value=""></option>';
                        while($kol = mysqli_fetch_assoc($resob2)){
                            echo  '<option value='.$kol['role_id'].' data-id="'.$kol['role_id'].'" data-name="'.$kol['role_name'].'">'.$kol['role_name'].'</option>';
                        }
                        echo "</select>";
                    }
            }
            echo'
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-success btn-sm" id="editTom" value="Сохранить"/>
        </div>
    </form>
    ';
echo'</div>';
}
?>
<script>
function funcBeforeObject () {
    $("#objecthere").text ("Ожидаю данные...");
}
function funcSuccessObject (data) {
    $("#objecthere").html(data);
}
$( document ).ready(function() {

    //кнопка копирования строк
    $('#copyBtn').click(function(){
        $('.copy1').append( $('#cpMe').eq(0).clone() );
        //$('.copy1').append( $('#empl_email').eq(0).clone().val('') );
        return false;
    });
    <?php
        foreach ($list as $key => $row) {
            //кнопка для редактирования
            if($_POST['empltype']=='executors')
            {

                //
                echo' $("#roleSelect'.$row['id'].'").val("'.$row['role_id'].'");';
                echo'
                $("#empl_update'.$row['id'].'").click(function(){
                    var id = '.$row['id'].';
                    var name = document.getElementById("employer'.$row['id'].'").value;
                    var email = document.getElementById("emplEmail'.$row['id'].'").value;
                    var role_id = document.getElementById("roleSelect'.$row['id'].'").value;
                    var login = document.getElementById("emplLogin'.$row['id'].'").value;
                    var pass = document.getElementById("emplPass'.$row['id'].'").value;
                    var action = "update";
                    var empltype = "'.$_POST['empltype'].'";
                    console.log(name, email,action,empltype,role_id, login, pass);
                    $.ajax ({
                        type:"POST",
                        url: "ajax/actions/empledit.php",
                        data: {
                                "name":name,
                                "id":id,
                                "email":email,
                                "action":action,
                                "empltype":empltype,
                                "login":login,
                                "pass":pass,
                                "role_id":role_id
                                },
                        beforeSend: funcBeforeObject,
                        success: funcSuccessObject
                    });
                });
            ';
            }
            else{
                echo'
                $("#empl_update'.$row['id'].'").click(function(){
                    var id = '.$row['id'].';
                    var name = document.getElementById("employer'.$row['id'].'").value;
                    var action = "update";
                    var empltype = "'.$_POST['empltype'].'";
                    $.ajax ({
                        type:"POST",
                        url: "ajax/actions/empledit.php",
                        data: {
                                "name":name,
                                "id":id,
                                "action":action,
                                "empltype":empltype
                                },
                        beforeSend: funcBeforeObject,
                        success: funcSuccessObject
                    });
                });
            ';
            }

            //кнопка для удаления 
            echo'
                $("#empl_delete'.$row['id'].'").click(function(){
                    var id = '.$row['id'].';
                    var action = "delete";
                    var empltype = "'.$_POST['empltype'].'";
                    $.ajax ({
                        type:"POST",
                        url: "ajax/actions/empledit.php",
                        data: {
                                "id":id,
                                "action":action,
                                "empltype":empltype,
                                },
                        beforeSend: funcBeforeObject,
                        success: funcSuccessObject
                    });
                });
            ';
        }
    ?>
    $('#formedit').on('submit', (e) => {
        e.preventDefault();
        const result = $('#formedit').serialize();
        var data = result;
        <?php echo'var empltype = "'.$_POST['empltype'].'";'; ?>
        console.log(data);
            $.ajax ({
                type:"POST",
                url: "ajax/actions/empledit.php",
                data: {"data":data,
                    "empltype":empltype
                        },
                beforeSend: funcBeforeObject,
                success: funcSuccessObject
            });
        return false;
    });

});
</script>