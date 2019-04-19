<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('Monthly Cronjob','geobg.png');

$mail = new Mail();


//esegue le richieste di caricamento dei cedolini e genera una tabella di riepilogo, logga e manda mail se errore o se vuota
try {
    $return = PMSBase::execChargesRequests();
    $mail->sendEmail($mail->tomail,$mail->toname,'PMS - Cedolini di sconto inseriti',$mailbody,$mail->copies);
    $content = $return;

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'PUNTI_SITO_UL',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Aggiunta dei punti al sito.",
        'origin' => 'DBServer.crm_punti.charges_requests',
        'destination' => 'Dom2DB.loc_cedolini',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}







echo "
<script type=\"text/javascript\">
    alert('Cedolini importati correttamente');
    window.location.replace('../menu.php');

</script>

";

builder::backToPage("/menu.php");