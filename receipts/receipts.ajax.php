<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 22/02/2019
 * Time: 11:35
 */

/*$rawarray = $_POST['rawarray'];
$grpname = $_POST['grpname'];
$delgrp = $_POST['delgrp'];*/

(isset($_POST['bookid'])) ? $bookid = $_POST['bookid'] : $bookid = '';





require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();

if ($_POST['command'] == 'rcptsupdate') {

    PMSBase::readCharges();
    Log::wLog('Cedolini aggiornati manualmente','Importazione cedolini');

}