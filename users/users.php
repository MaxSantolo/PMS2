<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'geobg.png';

isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['status']) ? $status = $_GET['status'] : $status = '';
$sql = DB::sqlTable('users','status',$status,'status');
if ($status != '') $status = ' - '. DB::transcode($status);

$title = 'Elenco utenti';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable');

?>


<p class="titolo"><?php echo $title. $status; ?></p>
<div class="text-sm-center">
    <a id='usrupd' href="#" class="btn btn-indigo">Aggiorna Utenti</a>
</div>

<!-- //forza accredito modale -->
    <div class="modal fade" id="Points" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content mb-12" style="background-color: rgba(250,250,250,.85)">
                <div class="modal-header" style="background-color: #<?php echo $thcolor;?>;color: white;font-weight: bold">
                    <h5 class="modal-title" id="exampleModalLabel">RETTIFICA I PUNTI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white">&times;</span>
                    </button>
                </div>
                <div class="modal-body dhtmlx_modal_box-12">
                    <form class="text-center border border-light p-2" action="" method="post">
                        <div class=" form- row mb-12">
                            <input class="form-control" id="bookid" name="bookid" value="" hidden />
                            <input class="form-control" id="date" name="date" value="" hidden />
                        </div>
                        <div class="form-row mb-12">
                            <P class="text-sm-center">INDICA LA QUANTIT&Agrave; DI PUNTI DA ACCREDITARE (ADDEBITARE)</P>
                            <BR>
                            <p class="text-sm-left small">Ricordate che l'accredito (addebito) sar&agrave; effettuato alle 23:30 del giorno in cui lo avete inserito.
                            Potete forzare l'accredito (addebito) immediatamente dalla schermata preposta.</p>
                        <input class="form-control rounded-0" id="points" name="points" />
                        </div>

                            <div class="form-group">
                                <label>AGGIUNGI UNA NOTA</label>
                                <textarea class="form-control rounded-0" id="note" name="note" rows="2" value=""></textarea>
                            </div>

                        <button class="btn btn-indigo" type="submit" name="addpoints">Rettifica</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php

    $db = new DB();
    $conn = $db->getProdConn('crm_punti');
    $users = $conn->query($sql);

	echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
              <thead style='background-color: #".$thcolor.";color: white'>
                <th>NOME</th>
                <th>CODICE FISCALE</th>
                <th>PARTITA IVA</th>
                <th>EMAIL</th>
                <th>TIPO</th>
                <th>STATO</th>
                <th>SALDO</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
              </thead>
              <tbody>";

		        while($user = $users->fetch_assoc()) {
                $urlmodulename = ucfirst($user['crmtype']);
                $check = builder::showCheck($user['active']);
                $email = builder::showEmail($user["bookingmail"],$user["crmemail"],'La mail di iscrizione e quella sul CRM non coincidono','/images/danger.png','/images/swc_mail_icon.png','Email CRM');
                $crmicon = builder::showCRMIcon($user['crmid'],$urlmodulename);
                $balance = DB::pointsBalance($user['bookid']);
                $now = PMSBase::Now();
                echo "<tr>
                           <td>".$user["company"]."</td>
                           <td>".$user["codfiscale"]."</td>
                           <td>".$user["partitaiva"]."</td>
                           <td>".$email."</td>
                           <td>".DB::transcode($user["crmtype"])."</td>
                           <td><IMG {$check} width='10' hspace='5' title='ID: {$user['bookid']}'>".DB::transcode($user["status"])."</td>
                           <td style='color: #0E64A0;font-weight: bold;text-align: right'>".$balance." punti</td>
                           <td width='24'><A HREF='#' data-toggle='modal' data-target='#Points' class='pointsdata' 
                                                data-id = '{$user['bookid']}' 
                                                data-date = '{$now}'
                                                ><IMG SRC = '/images/force_credit.png' width='24' TITLE='FORZA ACCREDITO'></td>
                           <td width='24'><a href='/credits/credits.php?cf={$user['codfiscale']}' onclick='showloader()'><IMG SRC='/images/plus_list.png' width='24' TITLE='VISUALIZZA ACCREDITI'></A></td>
                           <td width='24'><a href='/receipts/receipts.php?cf={$user['codfiscale']}&bookid={$user['bookid']}&thcolor=110e5e' onclick='showloader()'><IMG SRC='/images/minus_list.png' width='24' TITLE='VISUALIZZA CEDOLINI'></A></td>
                           <td width='24'>{$crmicon}</TD>
                      </tr>";
		        }
				echo "</tbody></table></div>";



echo '<BR>';
if (isset($_POST["points"])) {

    $bookid = $_POST['bookid'];
    $date = $_POST['date'];
    $points = intval($_POST['points']);
    $note = str_replace("'","''",$_POST['note']);
    if (is_int($points)) {
        PMSBase::forceCredit($conn, $bookid, $date, $points,'CORRECTION','ready',$note);
        Log::wLog("Rettificati {$points} punti relativi all'utente con ID: {$bookid}", "Accredito");
    } else echo "<script>alert('Inserire un valore intero');</script>";

}

   builder::Scripts();
   builder::configDataTable('DataTable','true',25,5,'asc');
   echo builder::createDatePicker(array('fromdate','todate'));
echo "
    <script>    
               $('#usrupd').on('click', function(e){ 
                   e.preventDefault();
                          if(confirm('Aggiorno gli utenti ora?')) {
                              $(\"#loader\").show();
                $.ajax({
                    type: 'POST',
                    url: 'users.ajax.php',
                    data: { command: 'updateusers'},
                    success: function () {
                              window.location.reload();
                    }
                    });
                }
                });                 
    </script>
";
//pass data to modal for notes
echo "
    <script>    
                $(document).on('click', '.pointsdata', function(){ 
                            $('.modal-body #bookid').val($(this).data('id'));
                            $('.modal-body #date').val($(this).data('date'));
                       });
    </script>";

    DB::dropConn($conn);
