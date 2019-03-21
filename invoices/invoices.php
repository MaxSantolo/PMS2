<?php

/**
 * Pagina vista del modello ricevute che vengono lette diretamente da eSolver con la connessione dedicata.
 * Parametri nell'URL il codice fiscale dell'utente collegato e lo status della ricevuta (per filtraggio)
 *
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'geobg.png';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['status']) ? $status = $_GET['status'] : $status = '';
isset($_GET['cf']) ? $cf = $_GET['cf'] : $cf = "";


//$sql = DB::sqlTable('v_credits','status',$status);

$title = 'Elenco Fatture';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable')
?>


<p class="titolo"><?php echo $title; ?></p>
<div class="text-sm-center">
    <a id='creditsupdate' href="#" class="btn btn-indigo">Aggiorna Fatture</a>
</div>
<!-- modale rettifica -->


<?php

    $db = new DB();
    $conn = $db->getProdConn('crm_punti');

    //prendo le voci di fattura ma non mostro i cedolini fatturati (li vado a leggere direttamente da Dom2)
    $invoices = $conn->query("SELECT * FROM v_invoices 
                                    WHERE invoice_status 
                                    LIKE '%{$status}%' AND 
                                    invoice_status NOT IN('imported','receipt') 
                                    ORDER BY status desc,points desc");

    $resstypeopts = DB::showOpt($conn,'','restype','credits_schema');

    //modale di rettifica delle fatture non riconosciute
    echo "
    <div class=\"modal fade\" id=\"invoicecorrect\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"invoicecorrect\" aria-hidden=\"true\">
        <div class=\"modal-dialog\" role=\"document\">
            <div class=\"modal-content\" style=\"background-color: rgba(250,250,250,.85)\">
                <div class=\"modal-header\" style=\"background-color: #529c56;color: white\">
                    <h5 class=\"modal-title\" id=\"exampleModalPreviewLabel\">Rettifica Fattura</h5>
                    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                        <span aria-hidden=\"true\" class=\"text-white\">&times;</span>
                    </button>
                </div>
                <div class=\"modal-body\" id=\"EdDupBody\">
                
                    <form class=\"text-center border border-light p-2\" action=\"\" method=\"post\">
                     <P class=\"text-sm-left small\">Non &egrave; stato possibile riconoscere automaticamente il contenuto di questa fattura. Controllate la descrizione qui sotto e definite che tipo e quante risorse sono coinvolte.</p>
                     <!-- campi nascosti per riferimento Ajax -->
                     <input id='creditid' name='creditid' value='' hidden/>
                     <input id='months' name='months' value='' hidden/>
                     <input id='date' name='date' value='' hidden/>
                     <input id='bookid' name='bookid' value='' hidden/>
                     <input id='invoiceid' name='invoiceid' value='' hidden/>
                     <div class=\"form-row mb-12\">
                        <label class='text-sm-center'>Descrizione fattura</label>
                        <input class='col-md-12' style='height: 50px;font-size: small' disabled id='description' name='description' />
                     </div>
                     <div class=\"form-row mb-12\">
                        <div class=\"col mb-sm-2\">
                            <label class='text-sm-center'>Che tipo di risorsa?</label>
                            <select name=\"frestype\" type=\"text\" id=\"frestype\" class=\"form-control form-control-chosen autocomplete\" placeholder=\"Risorsa\">
                               {$resstypeopts}
                            </select>
                        </div>
                        <div class=\"col mb-sm-2\">
                            <label class='text-sm-center'>Per quante risorse?</label>
                            <input name=\"fnumber\" type=\"text\" id=\"fnumber\" class=\"form-control form-control-chosen autocomplete\" placeholder=\"Numero\" />
                        </div>
                     </div>
                     <button class=\"btn btn-dark-green\" type=\"submit\" name=\"correct\">Identifica</button>
                 </form>
                 </div>
                    
                </div>
            </div>
        </div>
    ";

	echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
              <thead style='background-color: #".$thcolor.";color: white'>
                <th>UTENZA</th>
                <th>DAL</th>
                <th>AL</th>
                <th>DATA IMPORTAZIONE</th>
                <th>DESCRIZIONE</th>
                <th>STATO</th>
                <th>DATA ACCREDITO</th>
                <th>STATO ACCREDITO</th>
                <th>PUNTI</th>
                <th></th>
              </thead>
              <tbody>";

		        while($inv = $invoices->fetch_assoc()) {
                $fromdate = date('d-m-Y',strtotime($inv['DataFatturaDa']));
                $todate = date('d-m-Y',strtotime($inv['DataFatturaA']));
                $importdate = date('d-m-Y',strtotime($inv['importdate']));
                (!is_null($inv['credits_date'])) ? $creditdate = date('d-m-Y',strtotime($inv['credits_date'])) : $creditdate = '';
                $value = "&euro; ".round($inv['imponibile']/100,2);
                $company = $inv['company'] . " (CF: ".$inv['CodFiscale']." PIVA: ".$inv['PartitaIva'].")";
                ($inv['invoice_status'] == 'MANUAL') ? $hidden = '' : $hidden = 'hidden';

                echo "<tr>
                           <td><strong>{$company}</strong></td>
                           <td>{$fromdate}</td>
                           <td>{$todate}</td>
                           <td>{$importdate}</td>
                           <td>{$inv['DesEstesa']}</td>
                           
                           <td>".DB::transcode($inv["invoice_status"])."</td>
                           <td>{$creditdate}</td>
                           <td>".DB::transcode($inv['status'])."</td>
                           <td>{$inv['points']}</td>
                           <td width='24'><A HREF='#' data-toggle='modal' data-target='#invoicecorrect' class='invoicedata' 
                                                data-id = '{$inv['creditid']}' 
                                                data-description = '{$inv['DesEstesa']}'
                                                data-months = '{$inv['months']}'
                                                data-date = '{$inv['importdate']}'
                                                data-bookid = '{$inv['bookid']}'
                                                data-invoiceid = '{$inv['invoice_id']}'
                                                ><img src='/images/edit_invoice.png' width='24' TITLE='RETTIFICA RICONOSCIMENTO FATTURA' {$hidden}></TD>
                      </tr>";
		        }
				echo "</tbody></table></div>";

echo '<BR>';
//quando clicco sul modale calcolo il numero di punti da rettificare
if (isset($_POST["correct"])) {

    $creditid = $_POST['creditid'];
    $restype = $_POST['frestype'];
    $number = intval($_POST['fnumber']);
    $bookid = $_POST['bookid'];
    $months = $_POST['months'];
    $invoiceid = $_POST['invoiceid'];
    $importdate = date('Y-m-d',strtotime($_POST['date']));
    $firstresvalue = DB::getSchemaParam($conn,$restype,'PrimaRisorsa');
    $nextresvalue = DB::getSchemaParam($conn,$restype,'RisorseSuccessive');
    $now = PMSBase::Now();

    $points = ($firstresvalue+($number-1)*$nextresvalue)*$months;
    $points = PMSBase::promoPoints($conn,$points,$importdate,$restype);
       //se l'accredito esiste aggiorno altrimenti lo inserisco
    if ($creditid != '') {

        $conn->query("UPDATE credits SET origin = '{$restype}', status='ready', points='{$points}' WHERE id = '{$creditid}'"); //aggiorno l'accredito
        $message = "Rettificato accredito {$creditid} di {$points} punti";

    } else if ($bookid != '') {

        PMSBase::forceCredit($conn, $bookid, $now, $points, $restype);
        $message = "Aggiunto un accredito di {$points} a utente con id {$bookid}";

        }  else {

                PMSBase::forceCredit($conn,0,$now,$points,$restype,'lost',NULL,$invoiceid);
                $message = "Utente non registrato, accredito generato come perso.";
                echo "<script>alert(\".$message.\");</script>";

        }

    $conn->query("UPDATE esolver_invoices_importstatus SET status = 'adjusted' WHERE id = '{$invoiceid}'"); //aggiorno lo stato della fattura
    Log::wLog($message,'Rettifica'); //loggo il messaggio evinto dalle condizioni precedenti

    builder::refreshPage();

    }

   builder::Scripts();

//script che chiama il comando di aggiornamento forzoso delle fatture da eSolver
echo "
    <script>    
               $('#creditsupdate').on('click', function(e){ 
                   e.preventDefault();
                          if(confirm('Aggiorno le fatture ora?')) {
                              $(\"#loader\").show();
                $.ajax({
                    type: 'POST',
                    url: 'invoices.ajax.php',
                    data: { command: 'updateinvoices'},
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });                 
    </script>
";

//passo dati al modale per la rettifica
echo "
    <script>    
                $(document).on('click', '.invoicedata', function(){ 
                            $('.modal-body #creditid').val($(this).data('id'));
                            $('.modal-body #description').val($(this).data('description'));
                            $('.modal-body #months').val($(this).data('months'));
                            $('.modal-body #date').val($(this).data('date'));
                            $('.modal-body #bookid').val($(this).data('bookid'));
                            $('.modal-body #invoiceid').val($(this).data('invoiceid'));
                       });
    </script>";

    builder::configGroupedDataTable(0,'DataTable',true,100,0,asc,10,'4b7880');
    DB::dropConn($conn);
