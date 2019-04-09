<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PickLog.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('PMS 2.0a - Test Page','geobg.png');
builder::Navbar('DataTable');


//PMSBase::CheckCreateAccounts();






?>





<div style="width: 600px;margin: auto;padding: 10px"><br><br><br><br><img src="images/logo_pms.png" width="550"> </div>


<?php

$db = new DB();

//echo phpinfo();

/*echo "Sistemi: ";
$conn1 = $db->getSistemiConn();

print_r($conn1);

echo "<br>";


echo "<HR>";


echo "Dom2: ";
$conn = $db->getDom2Conn();

print_r($conn);


echo "<br>";



echo "<HR>";*/

echo PMSBase::ReadInvoices();






builder::Scripts();



?>

<script type="text/javascript">
    $(".autocomplete").chosen();
    $(".chzn-container").css({"left":"20%"});
</script>

</html>