<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');

//Блок функций для работы с клиентами и исполнителями

function update_employer($id, $name , $empltype, $email=NULL, $login=NULL, $pass=NULL, $role_id=NULL){
    $message='Обновляю запись в базе!';
    $error='Ошибка!';
    switch ($empltype) {
        case 'clients':
            $result = query("UPDATE client SET client_name = '$name' WHERE client_id = '$id'");
            if($result==false)
            {
                return $error;
            }else
            {
                return $message;
            }
            break;
        case 'executors':
            $result = query("UPDATE executor SET executor_name = '$name', email = '$email' WHERE executor_id = '$id'");
            $result2 = query("UPDATE autent 
            SET  login = '$login', pass = '$pass' , role_id = '$role_id',name = '$name' 
            WHERE exe_id = '$id'");
            if($result==false || $result2==false)
            {
                return $error;
            }else
            {
                return $message;
            }
            break;
        default:
            return $error;
        break;
    }
}

function delete_employer($id, $empltype){
    $message='Убираю запись в базе!';
    $error='Ошибка!';
    switch ($empltype) {
        case 'clients':
            $result = query("DELETE FROM client WHERE client_id=".$id);
            if($result==false)
            {
                return $error;
            }else
            {
                return $message;
            }
            break;
        case 'executors':
            $result = query("DELETE FROM executor WHERE executor_id=".$id);
            $result = query("DELETE FROM autent WHERE exe_id=".$id);
            if($result==false)
            {
                return $error;
            }else
            {
                return $message;
            }
            break;
        default:
            return $error;
        break;
    }
}
function add_employer($name, $empltype , $email=NULL, $login=NULL, $pass=NULL, $role_id=NULL){
    $message='Добавляю запись в базу!';
    $error='Ошибка!';
    switch ($empltype) {
        case 'clients':
            $select = query("SELECT client_id FROM client WHERE client_name='".$name."'");
            for ($getuser=array(); $col=mysqli_fetch_assoc($select) ;$getuser[]=$col);
            if(!empty($getuser))
            {
                $error.='<br>Такой клиент уже существует!';      
                return $error;
            }
            else
            {
                $result = query("INSERT INTO `client` (`client_name`) VALUES ('".$name."')"); 
                if($result==false)
                {
                    return $error;
                }else
                {
                    return $message;
                }
                break;
            }

        case 'executors':
            $select = query("SELECT executor_id FROM executor WHERE executor_name='".$name."'");
            for ($getuser=array(); $col=mysqli_fetch_assoc($select) ;$getuser[]=$col);
            if(!empty($getuser))
            {
                $error.='<br>Такой пользователь уже существует!';      
                return $error;
            }
            else
            {
                $result = query("INSERT INTO `executor` (`executor_name`, `email`) VALUES ('".$name."', '".$email."')"); 
                $select = query("SELECT executor_id FROM executor WHERE executor_name='".$name."'");
                for ($executor=array(); $row=mysqli_fetch_assoc($select) ;$executor[]=$row);
                $exe_id=$executor[0]['executor_id'];
                $result2 = query("INSERT INTO `autent` (`login`, `pass`,`name`,`exe_id`,`role_id`) VALUES (
                 '".$login."', '".$pass."', '".$name."', '".$exe_id."', '".$role_id."')");
                if($result==false || $result2==false)
                {
                    return $error;
                }else
                {
                    return $message;
                }
            }


            break;
        default:
            return $error;
        break;
    }
}
?>