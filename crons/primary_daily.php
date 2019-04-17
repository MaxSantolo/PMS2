<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PickLog.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('Primary Daily Cronjob','geobg.png');

$mail = new Mail();
$plog = new PickLog();

//loggato senza TRY->CATCH->FINALLY, direttamente in funzione
PMSBase::ReadInvoices();

//aggiorna i cedolini e logga
try {
    $return = PMSBase::readCharges();
    $content = $return;

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'DOM2_CEDOLINI_DL',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Lettura Cedolini da Dom2.",
        'origin' => 'Dom2Server.[loc_cedolini]',
        'destination' => 'DBServer.crm_punti.charges',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}

//loggato senza TRY->CATCH->FINALLY, direttamente in funzione
PMSBase::CheckCreateUsers();

//aggiorna/crea utenti e logga
try {
    $return = PMSBase::UpdateUsers();
    $content = "Stato degli utenti aggiornati dal sito. Effettuati {$return} aggiornamenti.";

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail($content);
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'AGGIORNA_UTENTI',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Aggiornamento degli utenti da Sito e CRM",
        'origin' => 'DBServer.crm_punti.users, Siteground.pickCent_23.wp_users',
        'destination' => 'DBServer.crm_punti.users',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}

//todo: riattivare al deployment
//PMSBase::createOnlineAccounts();

//aggiorna le fatture e logga
try {
    $return = PMSBase::updateInvoicesStatus();
    $content = $return;

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'FATTURE_RICONOSCIMENTO',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Riconoscimento delle righe di fattura per la generazione dei punti.",
        'origin' => 'DBServer.crm_punti.esolver_invoices',
        'destination' => 'DBServer.crm_punti.esolver_invoices_importstatus',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}




PMSBase::generateCredits();

PMSBase::generateContinuityCredits();

builder::backToPage('https://punti.pickcenter.com/menu.php');

