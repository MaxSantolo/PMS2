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
$mailbody = PMSBase::execChargesRequests();
$mail->sendEmail($mail->tomail,$mail->toname,'PMS - Cedolini di sconto inseriti',$mailbody,$mail->copies);






//builder::backToPage("/menu.php");