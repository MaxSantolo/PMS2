<?php

/**
 *Vista del modello dei cedolini. Permette di aggiungere una richiesta di sconto in punti che viene registrata nella
 *ta bella apposita, le richieste vengono poi analizzate da un CRON e vengono generati i dati per i cedolini (via mail).
 * Sono parametrizzati nell'URL l'id di Booking (bookid), il codice fiscale del cliente (cf) e la coppia mese/anno di riferimento.
 *

 **/

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'geobg.png';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';

isset($_GET['month']) ? $month = $_GET['month'] : $month = date('m');
isset($_GET['year']) ? $year = $_GET['year'] : $year = date('Y');

isset($_GET['cf']) ? $cf = $_GET['cf'] : $cf = "";
isset($_GET['bookid']) ? $bookid = $_GET['bookid'] : $bookid = "";


//$sql = DB::sqlTable('v_credits','status',$status);

$title = 'Elenco Cedolini Scontabili';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable')
?>

<p class="titolo"><?php echo $title; ?></p>
<div class="text-sm-center">
    <a id='rcptsupdate' href="#" class="btn btn-indigo">Aggiorna Cedolini</a>
</div>

<?php

    $db = new DB();
    $conn = $db->getProdConn('crm_punti');
    //prendo le voci di fattura ma, in questa fase, non mostro i cedolini

    $bwfrom = builder::cDateCreate("{$year}-{$month}-01"); //primo giorno del mese (in corso se non richiesto altrimenti del mese richiesto)
    $bwto = builder::cDateCreate("{$year}-{$month}",'Y-m-t'); //ultimo giorno del mese (in corso se non richiesto altrimenti del mese richiesto)
    $maxperc = $db->percPoints*100 . "%";
    $receipts = $conn->query("SELECT * FROM v_charges 
                                      WHERE codfiscale like '%{$cf}%' AND 
                                            (datestart BETWEEN '{$bwfrom}' AND '{$bwto}') 
                                    ORDER BY number ASC");

    //modale di richiesta addebito
    echo "
    <div class=\"modal fade\" id=\"spendpoints\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"spendpoints\" aria-hidden=\"true\">
        <div class=\"modal-dialog\" role=\"document\">
            <div class=\"modal-content\" style=\"background-color: rgba(250,250,250,.85)\">
                <div class=\"modal-header\" style=\"background-color: #cc0000;color: white\">
                    <h5 class=\"modal-title\" id=\"exampleModalPreviewLabel\">Spendi i punti</h5>
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\" class=\"text-white\">&times;</span>
                    </button>
                </div>
                <div class=\"modal-body\" id=\"EdDupBody\">
                
                    <form id='pointsform' class=\"text-center border border-light p-2\" action=\"\" method=\"post\">
                     <P class=\"text-sm-left small\">Il cliente pu&ograve; spendere al massimo un numero di punti pari al {$maxperc} del valore dei cedolini del periodo.<BR/> Indica nel campo punti la quantit&agrave; dei punti che il cliente desidera spendere e premi il pulsante \"Spendi\".<br>
                     L'icona rossa assegna il numero massimo di punti spendibili.</p>
                     <!-- campi nascosti per riferimento Ajax -->
                     <input id='fbookid' name='fbookid' value='' hidden/>
                     
                     
                     <input id='fmaxpoints' name='fmaxpoints' value='' hidden/>
                     <div class=\"form-row mb-12\">
                        <div class=\"col mb-sm-2\">
                            <label class='text-sm-center' for='ftotalvalue'>Totale cedolini</label>
                            <input name=\"ftotalvalue\" type=\"text\" id=\"ftotalvalue\" class=\"form-control disabled\" placeholder=\"\" />
                        </div>
                        
                             <div class='col mb-sm-2'>
                                <label class='text-sm-center' for='fbalance'>Il tuo saldo</label>
                                <input name=\"fbalance\" type=\"text\" id=\"fbalance\" class=\"form-control disabled \" placeholder=\"Punti\" />
                       </div>
                     </div>
                     <div class='form-row mb-12'>
                        <div class='col mb-sm-2'>
                            <label class='text-sm-center' for='fpoints'>Punti da spendere</label>
                            <div class='input-group'>
                                <input name=\"fpoints\" type=\"text\" id=\"fpoints\" class=\"form-control form-control-chosen \" placeholder=\"Punti\" /> 
                                <span class=\"input-group-addon\">   
                                 <img style='float: right' src='/images/maxpoints.png' width='36' HSPACE='10' title='MASSIMO DEI PUNTI' onclick='setMaxPoints()'>
                               </span>
                            </div>
                        </div>
                     </div>
                     
                     <button class=\"btn btn-red\" type=\"submit\" name=\"spend\" onclick='errorsCheck()'>Spendi</button>
                 </form>
                 </div>
                    
                </div>
            </div>
        </div>
    
    
    
    ";

	echo "<div id=\"risultati\" class=\"tableContainer\" style=\"font-size: medium\">
                   <P class='btn btn-outline-indigo font-weight-bold'>Periodo: {$month} - {$year}</P>";
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
                  <thead style='background-color: #".$thcolor.";color: white'>
                    <th>UTENZA</th>
                    <th>CEDOLINO</th>
                    <th>DESCRIZIONE</th>
                    <th>PERIODO</th>
                    <th>IMPORTO</th>
                  </thead>
              <tbody>";

		        while($rcp = $receipts->fetch_assoc()) {

		        $from = builder::cDateCreate($rcp['datestart'],'d-m-Y');
		        $to = builder::cDateCreate($rcp['dateend'],'d-m-Y');
                $value = "&euro; ".$rcp['value'];
                $company = $rcp['company'] . " (CF: ".$rcp['codfiscale']." PIVA: ".$rcp['partitaiva'].")";


                echo "<tr>
                           <td><strong>{$company}</strong></td>
                           <td>{$rcp['number']}</td>
                           <td>{$rcp['description']}</td>
                           <td>dal: {$from} al: {$to}</td>
                           <td style='text-align: right'>{$value}</td>
                      </tr>";
		        }
				echo "</tbody></table>";

                if ($cf != '') {

                    $totalvalue = PMSBase::calcPoints($cf,$bwfrom,$bwto)['totalvalue'];
                    $maxpoints = PMSBase::calcPoints($cf,$bwfrom,$bwto)['maxpoints'];

                    $balance = DB::pointsBalance($bookid);
                    (is_null($totalvalue)) ? $totalvalue = 0 : $totalvalue = PMSBase::calcPoints($cf,$bwfrom,$bwto)['totalvalue'];
                    (is_null($maxpoints)) ? $maxpoints = 0 : $maxpoints = PMSBase::calcPoints($cf,$bwfrom,$bwto)['maxpoints'];

                    echo "
                            <div class=\"text-sm-center\">
                                <a id='spendpointsbtn' href=\"#\" class=\"btn btn-red\" data-toggle='modal' data-target='#spendpoints'
                                            data-bookid='{$bookid}'
                                            data-balance = '{$balance}'
                                            data-totalvalue = '{$totalvalue}'
                                            data-maxpoints = '{$maxpoints}'
                                            >
                                Spendi Punti</a>
                            </div>
                    ";
                }

                echo "</div>";



echo '<BR>';
//quando clicco sul modale calcolo il numero di punti da rettificare
if (isset($_POST["spend"])) {

    $now = PMSBase::Now();
    $bookid = $_POST['fbookid'];
    $points = $_POST['fpoints'];
    $balancecorr = -$points;

    $conn->query("INSERT INTO debit_requests (date, bookid, points) VALUES ('{$now}','{$bookid}','{$points}')");

    //TODO: uncomment when ready
    //PMSBase::AddPoints($bookid,$balancecorr);

}

   builder::Scripts();
echo "
    <script>    
               $('#rcptsupdate').on('click', function(e){ 
                   e.preventDefault();
                          if(confirm('Aggiorno i cedolini ora?')) {
                              $(\"#loader\").show();
                $.ajax({
                    type: 'POST',
                    url: 'receipts.ajax.php',
                    data: { command: 'rcptsupdate'},
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });                 
    </script>
    <!-- calcolo del massimo dei punti spendibili -->
    <script>
    function setMaxPoints()
    {
    var theForm = document.forms[\"pointsform\"];
    var balance = parseInt(theForm.elements[\"fbalance\"].value);
    var maxpoints = parseInt(theForm.elements[\"fmaxpoints\"].value);
    
    console.log(balance);
    console.log(maxpoints);
    
    if ( balance <= maxpoints || maxpoints == 0 ) { theForm.elements['fpoints'].value = balance;} else theForm.elements['fpoints'].value = maxpoints; }
    </script>
    <!-- controllo degli errori nel form -->
    <script>
    function errorsCheck()
    {
        var theForm = document.forms[\"pointsform\"];
        var balance = parseInt(theForm.elements[\"fbalance\"].value);
        var maxpoints = parseInt(theForm.elements[\"fmaxpoints\"].value);
        var points =parseInt(theForm.elements[\"fpoints\"].value);
        var totalvalue = theForm.elements[\"ftotalvalue\"].value;
        var errorval = \"\";
        var errorval0 = \"\";
        
        console.log(totalvalue);
        
        if ( points > balance && totalvalue != \"\u20AC 0\") { errorval += \"Non puoi spendere pi\u00F9 punti del tuo attuale saldo.\\n\"; }
        if ( points > maxpoints && totalvalue != \"\u20AC 0\" ) { errorval += \"Non puoi spendere pi\u00F9 di \" + maxpoints + \" punti.\\n\"; }
        if ( totalvalue == \"\u20AC 0\") { errorval0 = \"Stai inserendo una richiesta sconto senza cedolini attivi. Il sistema controller\u00E0 i possibili sconti prima di emettere la prossima fattura.\"; }
        
        if (errorval != \"\" && errorval0 == \"\") {
            alert(errorval);
            $('#pointsform').attr('onsubmit','return false');
        } else if ( errorval0 == \"\" )$('#pointsform').attr('onsubmit','return true');
                  
        if (errorval0 != \"\") {
            alert(errorval0);
        }
    }
    
    </script>
";

//passo dati al modale per la spesa
echo "
    <script>    
                $('#spendpointsbtn').on('click', function(){ 
                            $('.modal-body #fbookid').val($(this).data('bookid'));
                            $('.modal-body #fbalance').val($(this).data('balance') + ' punti');
                            $('.modal-body #ftotalvalue').val('\u20AC ' + $(this).data('totalvalue'));
                            $('.modal-body #fmaxpoints').val($(this).data('maxpoints'));
                            
                       });
    </script>";

   builder::configGroupedDataTable(0,'DataTable',true,100,0,asc,10,'3964c3');
   DB::dropConn($conn);
