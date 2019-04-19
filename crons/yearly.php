<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('Yearly Cronjob','geobg.png');


//aggiorna i compleanni e gli anniversari, se ci sono errori o è vuoto l'array manda email. Logga tutto.
try {
    $return = PMSBase::updAnniversaryBirthdayNewYear();
    $content = "Aggiornati {$return} compleanni/anniversari.";

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'AGGIORNAMENTO_ANNIVERSARI',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Aggiornamento delle date di anniversario e di compleanno.",
        'origin' => 'DBServer.crm_punti.credits',
        'destination' => 'DBServer.crm_punti.credits',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}




