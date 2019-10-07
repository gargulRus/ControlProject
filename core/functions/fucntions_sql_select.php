<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/core/connect.php');

//Функция для получения проектов на редактирование
function getObjectsForEdit(){
$resob = query("SELECT * FROM `mainproject`");
if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
    if(mysqli_num_rows($resob)>0){ 
        $selectob = '<select name="obsel"  class="form-control input-sm" id="obsel">'; 
        $selectob.='<option></option>';
        while($row = mysqli_fetch_assoc($resob)){ 
            
            $selectob.= '<option value="'.$row['id'].'"
            data-id="'.$row['id'].'"
            data-name="'.$row['name'].'"
            data-service="'.$row['service'].'"
            data-speed="'.$row['speed'].'"
            data-address="'.$row['address'].'"
            data-status="'.$row['status'].'"
            data-state_id="'.$row['state_id'].'"
            data-executor_id="'.$row['executor_id'].'"
            data-client_id="'.$row['client_id'].'"
            data-date_plan="'.$row['date_plan'].'"
            >GA'.$row['name'].'</option>';
        } 
        $selectob.="</select>";
    }

    return $selectob;
}

//Функция для получения проектов и вывода в сводную таблицу
function getAbiobjects($stateid=null, $exeId=null){
    $objects = array();
    //смотрим какие параметры пришли в функцию и на основе них делаем выборку из базы
    if($stateid==NULL && $exeId == NULL)
    {
        $result2 = query("SELECT m.*, s.*, e.*,c.* FROM mainproject as m 
        left JOIN state as s ON m.state_id = s.state_id 
        left JOIN executor as e ON m.executor_id = e.executor_id 
        left JOIN client as c ON m.client_id = c.client_id 
        WHERE m.trash IS NULL");
    }
    elseif($stateid!=NULL && $exeId == NULL)
    {
        $result2 = query("SELECT m.*, s.*, e.*,c.* FROM mainproject as m 
        left JOIN state as s ON m.state_id = s.state_id 
        left JOIN executor as e ON m.executor_id = e.executor_id 
        left JOIN client as c ON m.client_id = c.client_id 
        WHERE m.state_id =".$stateid." AND m.trash IS NULL");
    }
    elseif($stateid==NULL && $exeId != NULL)
    {
        $result2 = query("SELECT m.*, s.*, e.*,c.* FROM mainproject as m 
        left JOIN state as s ON m.state_id = s.state_id 
        left JOIN executor as e ON m.executor_id = e.executor_id 
        left JOIN client as c ON m.client_id = c.client_id
        WHERE m.executor_id =".$exeId." AND m.trash IS NULL");
    }
    elseif($stateid!=NULL && $exeId!=NULL)
    {
        $result2 = query("SELECT m.*, s.*, e.*,c.* FROM mainproject as m 
        left JOIN state as s ON m.state_id = s.state_id 
        left JOIN executor as e ON m.executor_id = e.executor_id 
        left JOIN client as c ON m.client_id = c.client_id
        WHERE m.state_id =".$stateid." AND m.executor_id=".$exeId." AND m.trash IS NULL");
    }
    //обрабатываем полученный запрос
    for ($objects=array(); $row=mysqli_fetch_assoc($result2) ;$objects[]=$row);

    return $objects;
}

//Достаем сотрудников
function getExecutors(){
    $resob = query("SELECT executor_id, executor_name FROM `executor`");
    if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
    if(mysqli_num_rows($resob)>0){
            $selectExec = '<select name="exeSel"  class="form-control input-sm" id="exeSel">';
        $selectExec.= '<option value=""></option>';
            while($row = mysqli_fetch_assoc($resob)){
                $selectExec.= '<option value='.$row['executor_id'].' data-id="'.$row['executor_id'].'" data-name="'.$row['executor_name'].'">'.$row['executor_name'].'</option>';
            }
        $selectExec.="</select>";
        }

        return $selectExec;
}
function getExecutorsForPCreate(){
    $resob = query("SELECT executor_id, executor_name FROM `executor`");
    if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
    if(mysqli_num_rows($resob)>0){
            $selectExec = '<select name="exeSelPCreate"  class="form-control input-sm" id="exeSelPCreate">';
        $selectExec.= '<option value=""></option>';
            while($row = mysqli_fetch_assoc($resob)){
                $selectExec.= '<option value='.$row['executor_id'].' data-id="'.$row['executor_id'].'" data-name="'.$row['executor_name'].'">'.$row['executor_name'].'</option>';
            }
        $selectExec.="</select>";
        }

        return $selectExec;
}
//Достаем список состояний
function getStates() {
    
    $resob = query("SELECT state_id, state_name FROM `state`");
        if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
        if(mysqli_num_rows($resob)>0){
                $selectState = '<select name="statesel"  class="form-control input-sm" id="statesel">';
                $selectState.= '<option value=""></option>';
                while($row = mysqli_fetch_assoc($resob)){
                    $selectState.= '<option value='.$row['state_id'].' data-id="'.$row['state_id'].'" data-name="'.$row['state_name'].'">'.$row['state_name'].'</option>';
                }
            $selectState.="</select>";
            }
 
    return $selectState;
}
function getStatesForPCreate() {
    
    $resob = query("SELECT state_id, state_name FROM `state`");
        if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
        if(mysqli_num_rows($resob)>0){
                $selectState = '<select name="stateselPCreate"  class="form-control input-sm" id="stateselPCreate">';
                $selectState.= '<option value=""></option>';
                while($row = mysqli_fetch_assoc($resob)){
                    $selectState.= '<option value='.$row['state_id'].' data-id="'.$row['state_id'].'" data-name="'.$row['state_name'].'">'.$row['state_name'].'</option>';
                }
            $selectState.="</select>";
            }
 
    return $selectState;
}
//Достаем клиентов
function getClients(){
    $resob = query("SELECT client_id, client_name FROM `client`");
    if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
    if(mysqli_num_rows($resob)>0){
            $selectClients = '<select name="clientSel"  class="form-control input-sm" id="clientSel">';
        $selectClients.= '<option value=""></option>';
            while($row = mysqli_fetch_assoc($resob)){
                $selectClients.= '<option value='.$row['client_id'].' data-id="'.$row['client_id'].'" data-name="'.$row['client_name'].'">'.$row['client_name'].'</option>';
            }
        $selectClients.="</select>";
        }

        return $selectClients;
}
function getClientsForPCreate(){
    $resob = query("SELECT client_id, client_name FROM `client`");
    if(!$resob) exit("Ошибка запроса: ".mysqli_error($resob));
    if(mysqli_num_rows($resob)>0){
            $selectClients = '<select name="clientSelPCreate"  class="form-control input-sm" id="clientSelPCreate">';
        $selectClients.= '<option value=""></option>';
            while($row = mysqli_fetch_assoc($resob)){
                $selectClients.= '<option value='.$row['client_id'].' data-id="'.$row['client_id'].'" data-name="'.$row['client_name'].'">'.$row['client_name'].'</option>';
            }
        $selectClients.="</select>";
        }

        return $selectClients;
}

//Просто массивы с клиентами и исполнителями. 
function getClientsList($id=NULL){
    $list=array();
    if($id==NULL)
    {
        $res = query("SELECT client_id, client_name FROM `client`  ORDER BY client_name ASC");
        while($data=mysqli_fetch_assoc($res)){
            $list[]=array(
                'id'=>$data['client_id'],
                'name'=>$data['client_name'],
            );
        }
    }
    else
    {
        $res = query("SELECT client_id, client_name FROM `client` WHERE client_id = ".$id." ORDER BY client_name ASC");
        while($data=mysqli_fetch_assoc($res)){
            $list[]=array(
                'id'=>$data['client_id'],
                'name'=>$data['client_name'],
            );
        }
    }
    return $list;
}
function getExecutorsList($id=NULL){
    // $list=array();
    if($id==NULL)
    {
         $res = query("SELECT e.*, a.* FROM `executor` as e 
                         left JOIN autent as a ON e.executor_id=a.exe_id
                        ORDER BY executor_name ASC");
        while($data=mysqli_fetch_assoc($res)){
            $list[]=array(
                'id'=>$data['executor_id'],
                'name'=>$data['executor_name'],
                'email'=>$data['email'],
                'autid'=>$data['id'],
                'login'=>$data['login'],
                'pass'=>$data['pass'],
                'role'=>$data['role'],
                'role_id'=>$data['role_id'],
            );
        }
    }
    else{
        $res = query("SELECT e.*, a.* FROM `executor` as e 
                        left JOIN autent as a ON e.executor_id=a.exe_id
                         WHERE e.executor_id=".$id."
                        ORDER BY executor_name ASC");
        while($data=mysqli_fetch_assoc($res)){
            $list[]=array(
                'id'=>$data['executor_id'],
                'name'=>$data['executor_name'],
                'email'=>$data['email'],
                'autid'=>$data['id'],
                'login'=>$data['login'],
                'pass'=>$data['pass'],
                'role'=>$data['role'],
                'role_id'=>$data['role_id']
            );
        }
    }
    return $list;
}

?>