<?php

/**
 * Pagina della view con le opzioni (tabella: credits_schema) di selezione delle fatture per il riconoscimento del servizio.
*/


require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'geobg.png';
isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['status']) ? $status = $_GET['status'] : $status = '';


$title = 'OPZIONI - CONDIZIONI DI RICERCA';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable')
?>


<p class="titolo"><?php echo $title; ?></p>

<!-- //modifica opzioni modale -->
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
                                                    <BR><p class="text-sm-left small">Ricordate che l'accredito (addebito) sar&agrave; effettuato alle 23:30 del giorno in cui lo avete inserito.
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
    $params = $conn->query("SELECT * FROM v_where_options ORDER BY metakey");

	echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
              <thead style='background-color: #".$thcolor.";color: white'>
                <th>RISORSA</th>
                <th>VALORE</th>
                
              </thead>
              <tbody>";

		        while($opt = $params->fetch_assoc()) {

                $restype = DB::transcode($opt['restype']);
                echo "<tr>
                           <td>".$restype."</td>
                           <td>".$opt['value']."</td>
                      </tr>";
		        }
				echo "</tbody></table></div>";

   builder::Scripts();
   builder::configDataTable('DataTable',true,25,0,'asc');

   DB::dropConn($conn);
