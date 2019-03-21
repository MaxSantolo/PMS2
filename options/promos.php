<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'geobg.png';

isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['status']) ? $status = $_GET['status'] : $status = '';


$title = 'OPZIONI - PROMOZIONI';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable')
?>


<p class="titolo"><?php echo $title; ?></p>

<!-- //modifica opzioni modale -->
    <div class="modal fade" id="Options" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content mb-12" style="background-color: rgba(250,250,250,.85)">
                <div class="modal-header" style="background-color: #<?php echo $thcolor;?>;color: white;font-weight: bold">
                    <h5 class="modal-title" id="exampleModalLabel">MODIFICA PROMOZIONE</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" style="color: white">&times;</span>
                    </button>
                </div>
                <div class="modal-body dhtmlx_modal_box-12" id="optionsBody">
                    ...
                </div>
            </div>
        </div>
    </div>


<?php

    $db = new DB();
    $conn = $db->getProdConn('crm_punti');
    $params = $conn->query("SELECT * FROM v_promo_options ORDER BY metakey");

	echo '<div id="risultati" class="tableContainer" style="font-size: medium">';
		echo "<table id=\"DataTable\" class=\"table table-bordered table-striped table-hover table-sm datatableIntegration display compact\">
              <thead style='background-color: #".$thcolor.";color: white'>
                <th>RISORSA</th>
                <th>PARAMETRO</th>
                <th>VALORE</th>
                <th></th>
              </thead>
              <tbody>";

		        while($opt = $params->fetch_assoc()) {
                $restype = DB::transcode($opt['restype']);
                $metakey = DB::transcode($opt['metakey']);
                $value = DB::transcode($opt['value']);
                if ($opt['metakey'] == 'PromoValorePercento') $value .= '%';  //aggiunge il % alla visualizzazione della tabella
                if (($opt['metakey'] == 'PromoA' || $opt['metakey'] == 'PromoDa') && !is_null($value)) $value = date('d-m-Y', strtotime($value)); //se è un campo data non vuoto lo formatto per vederlo correttamente

                echo "<tr>
                           <td>".$restype."</td>
                           <td>".$metakey."</td>
                           <td>".$value."</td>
                           
                           <td width='24'><A HREF='#' class='optedit' data-toggle='modal' data-target='#Options'
                                                data-restype = '{$opt['restype']}'
                                                ><IMG SRC = '/images/edit2.png' width='24' TITLE='MODIFICA PROMOZIONE'></A></td>
                      </tr>";
		        }
				echo "</tbody></table></div>";



echo '<BR>';
if (isset($_POST["savePromoOptions"])) {

    $from = date('Y-m-d',strtotime($_POST['ffromdate']));
    $to = date('Y-m-d',strtotime($_POST['ftodate']));
    DB::savePromoOptions($conn,$_POST['frestype'],$from,$to,$_POST['fpointsperc'],$_POST['fpoints']);
    Log::wLog("Creata una campagna punti per {$_POST['frestype']}","Campagne");
    builder::refreshPage();

}

   builder::Scripts();
   builder::configGroupedDataTable(0,'DataTable',false,50,0,'asc',4);
   echo builder::createDatePicker(array('fromdate','todate'));
//ajax per modale
echo "
    <script>    
                $(document).on('click', '.optedit', function(e){
                            e.preventDefault();
                            var restype = $(this).data('restype');
                            console.log(restype);
                $.ajax({
                    type: 'POST',
                    url: 'options.ajax.php',
                    data: {restype: restype, command: 'editPromoOptions'},
                    success: function(data){
                        $('#optionsBody').html(data);}
                    });
                });
    </script>
";

    DB::dropConn($conn);
