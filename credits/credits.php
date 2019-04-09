<?php

/**
 *  Pagina di vista per gli accrediti generati automaticamente dalle fatture.
 *  Per la personalizzazione a singolo utente vengono passati i paramentri codice fiscale (cf) e status dell'accredito (status).
 *  TODO: passare anche bookid
 *
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

$title = 'Elenco accrediti';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable')
?>


<p class="titolo"><?php echo $title. ' - '. DB::transcode($status); ?></p>
<div class="text-sm-center">
    <a id='creditsupdate' href="#" class="btn btn-indigo">Aggiorna Accrediti</a>
</div>


<?php

    $db = new DB();
    $conn = $db->getProdConn('crm_punti');
    $credits = $conn->query("SELECT * FROM v_credits_list WHERE status LIKE '%{$status}%' AND codfiscale LIKE '%{$cf}%'");

	echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
              <thead style='background-color: #".$thcolor.";color: white'>
                <th>UTENZA</th>
                <th>CODICE FISCALE</th>
                <th>PARTITA IVA</th>
                <th>ORIGINE</th>
                <th>PUNTI</th>
                <th>DATA</th>
                <th>STATO</th>
                <th>EMAIL</th>
                <th>NOTE</th>
                
                <th></th>
              </thead>
              <tbody>";

		        while($credit = $credits->fetch_assoc()) {

                $date = date('d-m-Y',strtotime($credit['date']));
                $email = builder::showEmail($credit["bookingmail"],$credit["crmemail"],'La mail di iscrizione e quella sul CRM non coincidono','../images/danger.png');
                $crmicon = "";
                $button = builder::showEditCreditButton($credit['origin'],$credit['status']); //sceglie che pulsante mostrare

                echo "<tr>
                           <td>".$credit["company"]."</td>
                           <td>".$credit["codfiscale"]."</td>
                           <td>".$credit["partitaiva"]."</td>
                           <td>".DB::transcode($credit["origin"])."</td>
                           <td>".$credit["points"]."</td>
                           <td>".$date."</td>
                           <td>".DB::transcode($credit["status"])."</td>
                           <td>".$email."</td>
                           <td>{$credit['note']}</td>

                           
                           <td width='24'><A HREF='#' class='creditsdata' 
                                                data-id = '{$credit['id']}' 
                                                data-command = '{$button['command']}'
                                                data-question = '{$button['title']}'
                                                ><IMG SRC = '/images/{$button['image']}' width='24' TITLE='{$button['title']}'></td>
                           
                      </tr>";
		        }
				echo "</tbody></table></div>";



echo '<BR>';

   builder::Scripts();
   builder::configDataTable('DataTable','true',25,0,'asc');
   echo builder::createDatePicker(array('fromdate','todate'));

   //java per chiamata dell'aggiornamento accrediti
   echo "
    <script>    
               $('#creditsupdate').on('click', function(e){ 
                   e.preventDefault();
                          if(confirm('Aggiorno gli accrediti ora?')) {
                              $(\"#loader\").show();
                $.ajax({
                    type: 'POST',
                    url: 'credits.ajax.php',
                    data: { command: 'updatecredits'},
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });                 
    </script>
    ";
   //javascript per le chiamate dei pulsanti, poichè il pulsante è parametrizzato lo è anche la funzione ajax relativa
   echo "
    <script>    
               $(document).on('click', '.creditsdata', function(e){ 
                   e.preventDefault();
                   var creditid = $(this).data('id');
                   var command = $(this).data('command');
                   var question = $(this).data('question');
                          if(confirm(question + '?')) {
                              $(\"#loader\").show();
                $.ajax({
                    type: 'POST',
                    url: 'credits.ajax.php',
                    data: { command: command, creditid: creditid },
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });                 
    </script>
";

    DB::dropConn($conn);
