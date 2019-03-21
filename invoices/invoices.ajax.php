<?php
/**
 * Pagina AJAX per la pagina delle fatture
 */

/*$rawarray = $_POST['rawarray'];
$grpname = $_POST['grpname'];
$delgrp = $_POST['delgrp'];*/


require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

//anche nell'ajax tengo la sessione aperta per i log
builder::startSession();

//eseguo gli aggiornamenti relativi alle fatture e loggo
if ($_POST['command'] == 'updateinvoices') {

    PMSBase::ReadInvoices();
    PMSBase::CheckCreateUsers();
    PMSBase::updateInvoicesStatus();
    Log::wLog('Fatture aggiornate manualmente','Importazione fatture');

}