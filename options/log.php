
<?php



require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';

isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'sfondo.jpg';
isset($_GET['role']) ? $role = $_GET['role'] : $role = '';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['center']) ? $centro = $_GET['center'] : $center = '';

builder::startSession();
builder::Header('LOG EVENTI',$bg);
builder::Navbar('DataTable');
$contents = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/logs/general.log');

$log = str_replace(array("\r\n", "\r", "\n"),"<BR>",$contents);
echo "<p class='titolo'>LOG EVENTI</p>";

echo "<div class='logContainer'>$log</div>";





builder::Scripts();
builder::configDataTable('IcTable','true',10,1,'asc');
DB::dropConn($conn);
