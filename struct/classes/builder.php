<?php

class builder {

//costruisce l'intestazione di pagina (parametri sono il titolo della pagina e lo sfondo dalla cartella immagini)
public static function Header($title,$bg) {

    $bgurl = "../images/". $bg;
    header('Content-Type: text/html; charset=ISO-8859-1');
    header('<meta charset="UTF-8">');
    header ('<meta http-equiv="Content-type" content="text/html; charset=UTF-8">');
    echo '<html>
    <head>
        <title>'.$title. '</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="stylesheet" href="../../css/font-awesome/css/font-awesome.min.css">
        <link href="/mdbootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/mdbootstrap/css/mdb.min.css" rel="stylesheet">
        <link href="/mdbootstrap/css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/datatables/datatables.min.css"/>
        <link rel="stylesheet" type="text/css" href="/css/baseline.css"/>
        <link rel="stylesheet" type="text/css" href="/tech/datepicker/bootstrap-datepicker3.min.css"/>
        <link rel="stylesheet" type="text/css" href="/tech/timepicker/jquery.timepicker.min.css"/>
        <link rel="stylesheet" type="text/css" href="/tech/chosen/component-chosen.min.css"/>
        <style> body {
                background-image: url('.$bgurl.');
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: cover;
                background-color: #464646;
                }
        </style>
        <script>function showloader() {
                $("#loader").show();
              }
        </script>
    </head>
    <body>
    <div id="loader" class="loadingdiv">
        <img src="../../images/hourglass.gif" class="loadingimagediv">
    </div>
';


}

//include tutti gli script necessari
public static function Scripts() {


    echo '<script type="text/javascript" src="/mdbootstrap/js/jquery-3.3.1.min.js"></script>
          <script type="text/javascript" src="/mdbootstrap/js/bootstrap.min.js"></script>
          <script type="text/javascript" src="/mdbootstrap/js/popper.min.js"></script>
          <script type="text/javascript" src="/mdbootstrap/js/mdb.min.js"></script>
          <script type="text/javascript" src="/datatables/datatables.min.js"></script>
          <script type="text/javascript" src="/tech/datepicker/bootstrap-datepicker.min.js"></script>
          <script type="text/javascript" src="/tech/datepicker/bootstrap-datepicker.it.min.js"></script>
          <script type="text/javascript" src="/tech/timepicker/jquery.timepicker.min.js"></script>
          <script type="text/javascript" src="/tech/chosen/chosen.jquery.min.js"></script>';


    echo '<script type="text/javascript" src="/tech/jexport/tableExport.js"></script>
          <script type="text/javascript" src="/tech/jexport/jquery.base64.js"></script>';


    echo '<script>
            function stampa() {
                   window.print();
                }
        </script>';
    echo "
    <script type=\"text/javascript\">
    
        function checkAllIntercoms(e) {
            var aa = document.querySelectorAll(\"input[type=checkbox]\");
            for (var i = 0; i < aa.length; i++){
                aa[i].checked = e;
            }
        }    
    </script>
";
    echo '</body>';
}

//configura la DataTable con il nome della tabella, la paginazione, la lunghezza della paginazione, la colonna da ordinare (0 è la prima) e l'ordinamento
public static function configDataTable($tablename,$paginate,$lenght,$ordcol,$ascdesc) {
    echo '<script type="text/javascript" class="init">
                $(document).ready( function () {
                    $(\'#'.$tablename.'\').DataTable({
                        paging: '.$paginate.',    
                        "pageLength": '.$lenght.',
                        "order": [[ '.$ordcol.', "'.$ascdesc.'" ]],
                        "language": {
                            "decimal": ",",
                            "emptyTable": "Nessun risultato",
                            "info": "da _START_ a _END_ di _TOTAL_",
                            "infoEmpty": "Nessun Risultato",
                            "infoFiltered": "(filtrato da un totale di _MAX_ accessi)",
                            "infoPostFix": "",
                            "thousands": ".",
                            "lengthMenu": "Mostra _MENU_ risultati",
                            "loadingRecords": "Caricamento...",
                            "processing": "Elaborazione...",
                            "search": "Ricerca rapida:",
                            "zeroRecords": "Nessuna corrispondenza",
                            "paginate": {
                            "first": "Primo",
                            "last": "Ultimo",
                            "next": "Prossimo",
                            "previous": "Precedente"
                        },
                    "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                    }
                }
            });                   
    });
    </script >';
}

//come prima ma raggruppata
public static function configGroupedDataTable($grpclmn,$tablename,$paging,$lenght,$ordercolumn,$ascdesc,$grpcolspan,$grpcolor = '555555') {
    echo "<script>
            $(document).ready(function() {
                var groupColumn = ".$grpclmn.";
                var table = $('#".$tablename."').DataTable({
                    
                paging: '".$paging."',    
                \"pageLength\": '".$lenght."',
                \"order\": [[ ".$ordercolumn.", '".$ascdesc."' ]],
                \"language\": {
                               \"decimal\": \",\",
                               \"emptyTable\": \"Nessun risultato\",
                               \"info\": \"da _START_ a _END_ di _TOTAL_\",
                               \"infoEmpty\": \"Nessun Risultato\",
                               \"infoFiltered\": \"(filtrato da un totale di _MAX_)\",
                               \"infoPostFix\": \"\",
                               \"thousands\": \".\",
                               \"lengthMenu\": \"Mostra _MENU_ risultati\",
                               \"loadingRecords\": \"Caricamento...\",
                               \"processing\": \"Elaborazione...\",
                               \"search\": \"Ricerca rapida:\",
                               \"zeroRecords\": \"Nessuna corrispondenza\",
                               \"paginate\": {
                                              \"first\": \"Primo\",
                                              \"last\": \"Ultimo\",
                                              \"next\": \"Prossimo\",
                                              \"previous\": \"Precedente\"
                                            },
                               \"aria\": {
                                           \"sortAscending\": \": activate to sort column ascending\",
                                           \"sortDescending\": \": activate to sort column descending\"
                                         }
                            },
                \"columnDefs\": [
                        { \"visible\": false, \"targets\": groupColumn }
                    ],
                    \"order\": [[ groupColumn, 'asc' ]],
                    \"displayLength\": 25,
                    \"drawCallback\": function ( settings ) {
                        var api = this.api();
                        var rows = api.rows( {page:'current'} ).nodes();
                        var last=null;
             
                        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                            if ( last !== group ) {
                                $(rows).eq( i ).before(
                                    '<tr class=\"DataTableGroup\" style=\"background-color: #{$grpcolor}\"><td colspan=\"".$grpcolspan."\">'+group+'</td></tr>'
                                );
            
                                last = group;
                            }
                        } );
                    }
                } );
             
                // Order by the grouping
                $('#example tbody').on( 'click', 'tr.group', function () {
                    var currentOrder = table.order()[0];
                    if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                        table.order( [ groupColumn, 'desc' ] ).draw();
                    }
                    else {
                        table.order( [ groupColumn, 'asc' ] ).draw();
                    }
                } );
            } );
            
            </script>";
        }

//crea la barra di navigazione, la tabella dei parametri è quella scaricabile
public static function Navbar($table) {

    echo '<nav class="navbar navbar-expand-lg navbar-dark indigo">

    <a class="navbar-brand" href="\menu.php"><img src="/images/logo_pms.png" width="100"></a>
    <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">

     <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Fatture</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/invoices/invoices.php?thcolor=015d6e" onclick="showloader()">Raggruppate per Cliente</a>
          <a class="dropdown-item" href="/invoices/invoices.php?thcolor=59698d&status=manual" onclick="showloader()">Fatture da rettificare</a>
          <a class="dropdown-item" href="/receipts/receipts.php?thcolor=110e5e" onclick="showloader()">Cedolini</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Utenti</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/users/users.php" onclick="showloader()">Lista completa</a>
          <a class="dropdown-item" href="/users/users.php?status=active" onclick="showloader()">Utenti attivi</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/users/users.php?status=nocf" onclick="showloader()">Codice Fiscale mancante</a>
          <a class="dropdown-item" href="/users/users.php?status=nomail&thcolor=006273" onclick="showloader()">Email mancante</a>
          <a class="dropdown-item" href="/users/users.php?status=tosign&thcolor=d01add" onclick="showloader()">Da iscrivere</a>
          <a class="dropdown-item" href="/users/users.php?status=signed&thcolor=455114" onclick="showloader()">Iscritti non attivi</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accrediti</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/credits/credits.php" onclick="showloader()">Elenco completo</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/credits/credits.php?status=ready" onclick="showloader()">Pronti</a>
          <a class="dropdown-item" href="/credits/credits.php?status=lost" onclick="showloader()">Persi</a>
          <a class="dropdown-item" href="/credits/credits.php?status=credited" onclick="showloader()">Accreditati</a>
          <a class="dropdown-item" href="/credits/credits.php?status=zero" onclick="showloader()">Nulli</a>
          <a class="dropdown-item" href="/credits/credits.php?status=sentlost" onclick="showloader()">Persi Notificati</a>
          
        </div>
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Opzioni</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="/options/general.php?thcolor=000000" onclick="showloader()">Generali</a>
          <a class="dropdown-item" href="/options/conditions.php?thcolor=000000" onclick="showloader()">Condizioni</a>
          <a class="dropdown-item" href="/options/promos.php?thcolor=000000" onclick="showloader()">Promozioni</a>
          <a class="dropdown-item" href="/options/edit_ini_file.php?thcolor=000000" onclick="showloader()">Modifica file INI</a>
          <div class="dropdown-divider"></div>          
          <a class="dropdown-item" href="/options/log.php?thcolor=000&bg=optbg.jpg" onclick="showloader()">LOG</a>
          <a class="dropdown-item" href="/crons/primary_daily.php" onclick="showloader()">Aggiorna Tutto</a>
          <a class="dropdown-item" href="crons/monthly.php" onclick="showloader()">Manda Cedolini a Dom2</a>
        </div>
      </li>

      </ul>
      <ul class="navbar-nav pull-right">';

      if ($_SESSION["user_id"] != NULL) {echo '<li class="nav-item"><a class="nav-link" href="/logout.php" title="Click per logout">'.$_SESSION["user_name"].'</a></li>';}

      echo '<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 150px">Strumenti</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a href="" class="dropdown-item" data-toggle="modal" onclick="$(\'#'.$table.'\').tableExport({type:\'excel\',escape:\'false\'});">Esporta</a>
          <a href="" class="dropdown-item" data-toggle="modal" onclick="stampa()">Stampa</a>
          
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="mailto:max@swhub.io?subject=Segnalazione P.M.S." target="_blank">Segnala Problemi</a>
          <a class="dropdown-item" href="#">Versione '. $_SESSION['version']. '</a>
        </div>
      </li>  
       
      ';

    echo '</ul></div></nav>';

}

//avvia la sessione
public static function startSession() {
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("location:https://punti.pickcenter.com/index.php");
    }
}

//prepara il datepicker per tutte i campi nominati nell'array
public static function createDatePicker($array) {

    $script = '';
    $number = count($array);
    for($i=0;$i<$number;$i++) {
        $script .= "
        <script>
            $('#".$array[$i]."').datepicker({
                language: \"it\",
                daysOfWeekDisabled: \"0,6\",
                autoclose: true,
                format: 'dd-mm-yyyy'
            });
          </script>
        ";
    }
    return $script;
}

//prepara il timepicker per tutte i campi nominati nell'array
    public static function createTimePicker($array) {

        $script = '';
        $number = count($array);
        for($i=0;$i<$number;$i++) {
            $script .= "
        <script>
                $('#".$array[$i]."').timepicker({
                'timeFormat': 'H:i',
                'minTime': '07:00am',
                'maxTime': '10:00pm'
                
                });
        </script>
        ";
        }
        return $script;
    }

//carica immagine v verde se valore 1 o x rossa se 0
public static function showCheck($value) {

    if ($value == 1) {
        return $imgstr = "SRC='/images/check.png' width='20'";
        } else return $imgstr = "SRC='/images/red-x.png' width='20'";

}

//mostra un avvertimento se le mail passate non coincidono
public static function showEmail($primary,$secondary = '',$message = '',$image= '',$secondaryimg='',$secondarymessage='') {

    $primary = strtolower($primary);
    $secondary = strtolower($secondary);

    if ($primary == $secondary || $secondary == '') {
        $op = "<A HREF='mailto:{$primary}'>{$primary}</A>";
    }
    if ($primary != $secondary && $primary != '') {
        $op = "<IMG SRC='{$image}' width='20' hspace='5' TITLE='{$message} ({$secondary})'><A HREF='mailto:{$primary}'>{$primary} </A>";
    }
    if ($secondary != '' && $primary == '') {
        $op = "<IMG SRC='{$secondaryimg}' TITLE='{$secondarymessage}' width='20' hspace='5'><A HREF='mailto:{$secondary}'>{$secondary} </A>";
    }

    return $op;
}

//mostra l'icona del CRM con un messaggio
public static function showCRMIcon($crmid,$urlmodulename) {
    if ($crmid!= '') {
        $op = "<A HREF='http://crm.pickcenter.com/index.php?module={$urlmodulename}s&action=DetailView&record={$crmid}' target='_blank'>
                                <IMG SRC='../images/swc_icon.png' width='24' title='MODIFICA DATI SUL CRM'></a>";
    } else $op = " <IMG SRC='../images/no_edit.png' width='24' title='CONTATTO/AZIENDA NON PRESENTE SUL CRM'>";
    return $op;
}

//legge il file ini per le configurazioni generali
public function readIniFile() {

    return $ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/points.ini',true);

}

//mostra icona di modifica dell'accredito: se bonus solo rimozione, se pronto o accreditato niente altrimenti modifica
public static function showEditCreditButton($origin,$status) {

    if ($origin == 'CORRECTION' && $status = 'ready') {
            $out['image'] = 'file_delete.png';
            $out['command'] = 'deletecredit';
            $out['title'] = 'CANCELLO ACCREDITO';
            return $out;
        }
    if ($status == 'ready' || $status == 'credited' || $status == 'zero') {
            $out['image'] = 'disabled.png';
            $out['command'] = '';
            $out['title'] = 'NESSUN COMANDO DISPONIBILE';
    } else {
            $out['image'] = 'enable.png';
            $out['command'] = 'enablecredit';
            $out['title'] = 'ABILITO ACCREDITO';
            return $out;
    }
    return $out;
}

//genera il modale per la modifica delle opzioni generali
public static function modalGeneralOption($restype,$btnname,$btntext) {

    $db = new DB();
    $conn = $db->getProdConn('crm_punti');

    //leggo iterativamente i parametri per mostrarli nel modale
    $cycle = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'Periodo'")->fetch_assoc();
    $cyclev = $cycle['value']; //valore
    $cyclel = DB::transcode('Periodo'); //etichetta
    $cyclesv = DB::transcode($cyclev); //etichetta elenco
    $bonusfid = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'Fedelta'")->fetch_assoc();
    $bonusfidv = $bonusfid['value'];
    $bonusfidl = DB::transcode('Fedelta');
    $renewmonths = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'MesiRinnovo'")->fetch_assoc();
    $renewmonthsv = $renewmonths['value'];
    $renewmonthsl = DB::transcode('MesiRinnovo');
    $firstres = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'PrimaRisorsa'")->fetch_assoc();
    $firstresv = $firstres['value'];
    $firstresl = DB::transcode('PrimaRisorsa');
    $nexres = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'RisorseSuccessive'")->fetch_assoc();
    $nextresv = $nexres['value'];
    $nextresl = DB::transcode('RisorseSuccessive');

    $form = "
    <form class='text-center border border-light p-2' action='' method='post'>
        <div class='form-row mb-12'>
            <div class='col'>
                <input class='form-control' id='frestype' name='frestype' value='{$restype}' />
            </div>
        </div>
        <div class='form-row mb-2'>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$cyclel}</label>
                <select name='fcycle' type='text' id='fcycle' class='form-control form-control-chosen'>
                    <option value='{$cyclev}' selected>{$cyclesv}</option>
                    <option value='true'>Vero</option>
                    <option value='false'>Falso</option>
                </select>
            </div>
        </div>
        <div class='form-row mb-2'>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$renewmonthsl}</label>
                <input name='frenewmonths' type='text' id='frenewmonths' class='form-control' value='{$renewmonthsv}' required>
            </div>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$bonusfidl}</label>
                <input  name='fbonusfid' type='text' id='fbonusfid' class='form-control' value='{$bonusfidv}' required>
            </div>
        </div>
        <div class='form-row mb-2'>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$firstresl}</label>
                <input name='ffirstres' type='text' id='ffirstres' class='form-control' value='{$firstresv}' required>
            </div>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$nextresl}</label>
                <input  name='fnextres' type='text' id='fnextres' class='form-control' value='{$nextresv}' required>
            </div>
        </div>        
        <div class='form-row mt-4'>
            <button class='btn btn-black m-auto' type='submit' name='{$btnname}' onclick='showloader()'>{$btntext}</button>
        </div>                
    </form>
    ";

    return $form;

}

//genera il modale per la modifica delle opzioni generali
public static function modalPromoOption($restype,$btnname,$btntext) {

        $db = new DB();
        $conn = $db->getProdConn('crm_punti');

        //leggo iterativamente i parametri per mostrarli nel modale
        $promopoints = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'PromoValorePunti'")->fetch_assoc();
        $promopointsv = $promopoints['value']; //valore
        $promopointsl = substr(DB::transcode('PromoValorePunti'),0,-15); //etichetta

        $promopointsperc = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'PromoValorePercento'")->fetch_assoc();
        $promopointspercv = $promopointsperc['value'];
        $promopointspercl = substr(DB::transcode('PromoValorePercento'),0,-15);

        $fromdate = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'PromoDa'")->fetch_assoc();
        $fromdatev = date('d-m-Y',strtotime($fromdate['value']));
        $fromdatel = DB::transcode('PromoDa');

        $todate = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = 'PromoA'")->fetch_assoc();
        $todatev = date('d-m-Y', strtotime($todate['value']));
        $todatel = DB::transcode('PromoA');

        $form = "
    <form class='text-center border border-light p-2' action='' method='post'>
        <div class='form-row mb-12'>
            <div class='col'>
                <input class='form-control' id='frestype' name='frestype' value='{$restype}' />
            </div>
        </div>
        <div class='form-row mb-2'>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$fromdatel}</label>
                <input name='ffromdate' type='text' id='ffromdate' class='form-control' value='{$fromdatev}'>
            </div>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$todatel}</label>
                <input  name='ftodate' type='text' id='ftodate' class='form-control' value='{$todatev}'>
            </div>
        </div>
        <div class='form-row mb-2'>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$promopointsl}</label>
                <input name='fpoints' type='text' id='fpoints' class='form-control' value='{$promopointsv}' required>
            </div>
            <div class='col'>
                <label class='col-form-label-sm mb-sm-0'>{$promopointspercl}</label>
                <input  name='fpointsperc' type='text' id='fpointsperc' class='form-control' value='{$promopointspercv}%' required>
            </div>
        </div>        
        <div class='form-row mt-4'>
            <button class='btn btn-black m-auto' type='submit' name='{$btnname}' onclick='showloader()'>{$btntext}</button>
        </div>                
    </form>
    ";
        $form .= builder::createDatePicker(array('ffromdate','ftodate'));
        return $form;

    }

//ricarica la pagina corrente
public static function refreshPage() {
    echo "<meta http-equiv='refresh' content='0'>";
}

//torna a pagina (con percorso)
public static function backToPage($page) {
    echo "<script>window.location = '{$page}'</script>";
}

//crea una data in base al formato
public static  function cDateCreate($datestring,$format = 'Y-m-d') {
    $d = new DateTime($datestring);
    $dformat = $d->format($format);
    return $dformat;

}

}