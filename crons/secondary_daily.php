<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('Secondary Daily Cronjob','geobg.png');

$plog = New PickLog();
$mail = New Mail();


//aggiunge al sito i cedolini scaricati del mese in corso, logga e manda mail in caso di errore
try {
    $return = PMSBase::uploadCharges();
    $content = "Sono stati aggiunti/aggiornati $return cedolini al sito.";

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'CEDOLINI_SITO_UL',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Aggiornamento dei cedolini online.",
        'origin' => 'DBServer.crm_punti.v_charges',
        'destination' => 'Siteground.pickcent_23.wpsd_wc_points_rewards_charges',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}

//aggiunge al sito i punti per fatture e anniversari, scarta quelli per chi non è registrato. Avverte tutti per email e logga eventuali errori.
try {
    $return = PMSBase::addCreditsToSite();
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
        'origin' => 'DBServer.crm_punti.credits',
        'destination' => 'Siteground.pickcent_23.wpsd_wc_points_rewards_points',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}

//scarica dal sito le richieste di accredito
try {
    $return = PMSBase::downloadChargesRequests();
    $content = "Sono state scaricate {$return} richieste di addebito punti sui cedolini.";

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'RICHIESTE_ACCREDITO_DL',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Scaricamento delle richieste di accredito dal sito.",
        'origin' => 'Siteground.pickcent_23.wpsd_wc_points_rewards_charges_requests',
        'destination' => 'DBServer.crm_punti.debit_requests',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}



