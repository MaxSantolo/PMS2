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

/*$db = new DB;
$conn_sistemi = $db->getSistemiConn();
$conn_amanda = $db->getProdConn('crm_punti');
$query = "
              SELECT DocUniRigheFattVen.IdDocumento, 
                     cast(DocUniRigheFattVen.DatiIndDataPeriodoDA as varchar) as InvoiceDateFrom,
                     cast(DocUniRigheFattVen.DatiIndDataPeriodoA as varchar) as InvoiceDateTo,
                     ImportoValuta, DesEstesa, DocUniTestata.AnnoCompetenzaIva, 
                     DocUniTestata.IdAnagGen, AnagrGenCliFor.PartitaIva, AnagrGenCliFor.CodFiscale, AnagrGenIndirizzi.RagSoc1, AnagrGenIndirizzi.RagSoc2, AnagrGenIndirizzi.IndirEmail
              FROM 
                DocUniRigheFattVen
                JOIN DocUniTestata ON (DocUniTestata.IdDocumento = DocUniRigheFattVen.IdDocumento)
                JOIN AnagrGenCliFor ON (DocUniTestata.IdAnagGen = AnagrGenCliFor.IdAnagGen)
                JOIN AnagrGenIndirizzi ON (AnagrGenCliFor.IdAnagGen = AnagrGenIndirizzi.IdAnagGen)
              WHERE 
                DocUniTestata.AnnoCompetenzaIva >= YEAR(getdate())
                AND DocUniTestata.MeseCompetenzaLiquid >= MONTH(getdate()) 
                AND DesEstesa NOT LIKE '%Storno%'
                AND PartitaIva != '12599371007' -- esclude i rapporti Pick Center / Pick Center Roma
              ORDER BY DocUniRigheFattVen.IdDocumento DESC 
        ";

$invoices_array = odbc_exec($conn_sistemi, $query);

( odbc_errormsg($conn_sistemi) ) ? $msg = "errore " . odbc_errormsg($conn_sistemi) : $msg = "OK!";
echo $msg;*/

PMSBase::CheckCreateUsers();







builder::Scripts();



?>

<script type="text/javascript">
    $(".autocomplete").chosen();
    $(".chzn-container").css({"left":"20%"});
</script>

</html>