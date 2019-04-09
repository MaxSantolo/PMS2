<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('Secondary Daily Cronjob','geobg.png');

PMSBase::uploadCharges();
PMSBase::addCreditsToSite();
PMSBase::downloadChargesRequests();

$mail = new Mail();
$mail->sendAccountsErrors();