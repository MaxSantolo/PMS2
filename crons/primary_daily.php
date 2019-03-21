<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('Primary Daily Cronjob','geobg.png');


PMSBase::ReadInvoices();
PMSBase::readCharges();
PMSBase::CheckCreateUsers();
PMSBase::UpdateUsers();
PMSBase::createOnlineAccounts();
PMSBase::updateInvoicesStatus();
PMSBase::generateCredits();
PMSBase::generateContinuityCredits();

/* //todo: riattivare al deploy aggiorna sito
PMSBase::uploadCharges();
*/

builder::backToPage('https://punti.pickcenter.com/menu.php');

