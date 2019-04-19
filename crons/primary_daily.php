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
//crea gli utenti online manda la mail e logga
/*try {
    $return = PMSBase::createOnlineAccounts();
    $content = "Iscritti {$return} utenti e mandate le rispettive email di accesso.";

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'CREA_UTENTI_ONLINE',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Creazione Account Online per primo accesso e comunicazione via email al cliente.",
        'origin' => 'DBServer.crm_punti.users',
        'destination' => 'Siteground.pickcent_23.wpsd_users, Siteground.pickcent_23.wpsd_users_meta,',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}*/

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

//genera gli accrediti da effettuare provenienti da: fatture, continuità contrattuale e anniversari. Aggiunge a tutto un log e manda mail su errore
try {
    $return = PMSBase::generateCredits();
    $content = "Sono stati generati $return accrediti derivanti da fatture e anniversari.";

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'GENERAZIONE_ACCREDITI',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Generazione degli accrediti di punti dipendentemente dalle righe di fattura riconosciute",
        'origin' => 'DBServer.crm_punti.users, DBServer.crm_punti.esolver_invoices',
        'destination' => 'DBServer.crm_punti.credits',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}

//todo: riattivare al deployment
//calcola la continuità contrattuale, manda la mail, logga e aggiunge punti al sito
/*try {
    $return = PMSBase::generateContinuityCredits();
    $content = $return;

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'GENERAZIONE_ACCREDITI_CONTINUITA',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Genera gli accrediti per continuità contrattuale e li aggiunge al sito.",
        'origin' => 'DBServer.crm_punti.continuity, DBServer.crm_punti.credits, DBServer.crm_punti.users',
        'destination' => 'DBServer.crm_punti.continuity',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}*/

//manda email per utenti con profili da completare
try {
    $return = $mail->sendAccountsErrors();
    $content = $return;

} catch (Exception $e) {
    $content = $e->getMessage();
    $smail = $mail->sendErrorEmail("<PRE>". $content . "</PRE>");
    $logTitle = "Errore";
} finally {
    $params = array(
        'app' => 'PMS',
        'action' => 'USERS_2_COMPLETE',
        'content' => $content,
        'user' => $_SESSION['user_name'],
        'description' => "Invio della mail con gli account da completare sul CRM",
        'origin' => 'DBServer.crm_punti.users',
        'destination' => 'Email',);
    Log::wLog($content,$logTitle);
    $plog->sendLog($params);
}




builder::backToPage('https://punti.pickcenter.com/menu.php');

