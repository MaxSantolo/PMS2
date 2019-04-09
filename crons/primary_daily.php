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


echo PMSBase::ReadInvoices();
PMSBase::readCharges();
PMSBase::CheckCreateUsers();
PMSBase::UpdateUsers();
//todo: riattivare al deployment
//PMSBase::createOnlineAccounts();
PMSBase::updateInvoicesStatus();
PMSBase::generateCredits();
PMSBase::generateContinuityCredits();

builder::backToPage('https://punti.pickcenter.com/menu.php');

