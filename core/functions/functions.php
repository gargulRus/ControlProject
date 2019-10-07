<?php
require_once('fucntions_sql_select.php');
require_once('fucntions_sql_save.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/plugins/PHPMailer/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/core/plugins/PHPMailer/SMTP.php');


//Функция отправки EMAIL письма (используется PHPMailer)
/*
goMail(array(
    'body'=>'Тестовое сообщение HTML с <b>Жирным текстом</b>',
    'to'=>array( 'gargul@yandex.ru'),
    'subject'=>'Тема письма'
));
*/
function goMail( $executorEmail, $subject, $message){

    $mail = new PHPMailer;

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = '	smtp.mail.ru';                       // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'it@giprozdraw.ru';             // SMTP username
    $mail->Password = 'dasnat917';                         // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to
    $mail->CharSet = 'UTF-8';
    
    $mail->setFrom('it@giprozdraw.ru', 'V-Bot');
    
    //Формируем адрес на который отправим
    // if( isset($params['to'][1]) ){
    //     $mail->addAddress( $params['to'][0], $params['to'][1] );
    // }else{
    //     $mail->addAddress( $params['to'][0] );
    // }

    $mail->addAddress($executorEmail, '');               // Name is optional
    //$mail->addReplyTo('it@domkihot.ru', 'Технический отдел'); //Куда отправить ответ
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                    // Set email format to HTML

    $mail->Subject = $subject;
    $mail->Body    = $message;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if( !$mail->send() ){
        return array( 'error'=>$mail->ErrorInfo );
    } else {
        return array( 'success'=>true );
    }
}


?>