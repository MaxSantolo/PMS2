<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/tech/class/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Mail.php';

builder::startSession();
builder::Header('PMS 2.0a - Test Page','geobg.png');
builder::Navbar('DataTable');


//PMSBase::CheckCreateAccounts();






?>





<div style="width: 600px;margin: auto;padding: 10px"><br><br><br><br><img src="images/logo_pms.png" width="550"> </div>


<?php

$db = new DB;
$conn = $db->getProdConn('crm_punti');

/*$data = DB::arrayMaxPointsCategory($conn,227);
var_dump($data);
echo "<hr>";
var_dump(DB::chargesToDo($data,1781));*/







/*$mail = new Mail();
$smail = $mail->sendEmail('max@swhub.io','MAx','Test',$body);*/

$conn = $db->getSiteConn();




//builder::backToPage("/menu.php");




builder::Scripts();
//test completo
/*PMSBase::ReadInvoices();
PMSBase::CheckCreateUsers();*/
//PMSBase::UpdateUsers();
//PMSBase::updateInvoicesStatus();




//Mail::sendAccountsErrors('max@swhub.io','MS','Test');










/*$test = PMSBase::GetCRMData('CLRPLA82C31H5010');
var_dump($test);*/




//echo $test = substr('SNTMSM76H30H501J', 9, 2);
//

/*echo PMSBase::updAnniversaryBirthdayNewYear();*/



//PMSBase::calcMonthsContinuity($conn,'BRVCLD61S28H501B','RECCOMPLPERS',5,'2019-05-04',2);


?>

<script type="text/javascript">
    $(".autocomplete").chosen();
    $(".chzn-container").css({"left":"20%"});
</script>

</html>