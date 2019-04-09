<?php
/**
 * Pagina AJAX per la pagina degli accrediti
*/

$creditid = $_POST['creditid'];


require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

//sessione aperta per avere log nominali
builder::startSession();

$db = new DB();
$conn = $db->getProdConn('crm_punti');

//esegue l'aggiornamento degli accrediti se viene cliccato il tasto apposito sulla pagina credits.php
if ($_POST['command'] == 'updatecredits') {
    PMSBase::generateCredits();
    PMSBase::generateContinuityCredits();
    Log::wLog("Aggiornati manualmente gli accrediti","Accrediti");
}

//elimina l'accredito forzoso (non gli automatici) se viene cliccato il tasto appostito sulla pagina credits.php
if ($_POST['command'] == 'deletecredit') {
    $conn->query("DELETE FROM credits WHERE id = {$creditid}");
    Log::wLog("Eliminato l'accredito bonus con id: {$creditid}","Accrediti");
}

//ripristina l'accredito scaduto o perso se viene cliccato il tasto apposito sulla pagina credits.php
if ($_POST['command'] == 'enablecredit') {
    $conn->query("UPDATE credits SET status = 'ready' WHERE id = '{$creditid}'");
    $data = $conn->query("SELECT bookid,points FROM credits WHERE id = '{$creditid}'")->fetch_assoc();
    if ($data['bookid'] != 0) {
        // PMSBase::AddPoints($data['bookid'],$data['points']); //todo: aggiunge i punti immediatamente al sito
        Log::wLog("Ripristinato l'accredito con id: {$creditid}","Accrediti");
    }
}
