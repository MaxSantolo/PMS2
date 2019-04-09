<?php
/**
 * Created by PhpStorm.
 * User: msantolo
 * Date: 30/10/2018
 * Time: 10:46
 */

class DB
{
    //carico i parametri di connessione dall'ini
    function __construct()    {
        $ini = parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/points.ini',true);
        //Server Centralino MySQL
        $this->PBX = $ini['DB']['PBX'];
        $this->PBXUserName = $ini['DB']['PBXUserName'];
        $this->PBXPassword = $ini['DB']['PBXPassword'];
        //DB_Server MySQL
        $this->Amanda = $ini['DB']['Amanda'];
        $this->AmandaUserName = $ini['DB']['AmandaUserName'];
        $this->AmandaPassword = $ini['DB']['AmandaPassword'];
        //DB Sito - database fisso 'pick_cent23' MySQL
        $this->SiteGround = $ini['DB']['SiteGround'];
        $this->SiteGroundUserName = $ini['DB']['SiteGroundUserName'];
        $this->SiteGroundPassword = $ini['DB']['SiteGroundPassword'];
        $this->SiteGroundDB = $ini['DB']['SiteGroundDB'];
        //DB eSolver - database fisso 'ESOLVER' SQLServer
        $this->eSolver = $ini['DB']['eSolver'];
        $this->eSolverUserName = $ini['DB']['eSolverUserName'];
        $this->eSolverPassword = $ini['DB']['eSolverPassword'];
        $this->eSolverDB = $ini['DB']['eSolverDB'];
        //Schema Punti
        $this->BPFirstLogin = $ini['Punti']['Iscrizione'];
        $this->BPBirthday = $ini['Punti']['Compleanno'];
        $this->BPAnniversary = $ini['Punti']['Anniversario'];
        //Database Dom2
        $this->Dom2 = $ini['DB']['Dom2'];
        $this->Dom2UserName = $ini['DB']['Dom2UserName'];
        $this->Dom2Password = $ini['DB']['Dom2Password'];
        $this->Dom2DB = $ini['DB']['Dom2DB'];

        //Tolleranze per continuità contrattuale per accrediti bonus
        $this->CToleranceDays = $ini['Rinnovi']['Tolleranza'];

        //Percentuale di punti spendibili
        $this->percPoints = $ini['Punti']['PercentualeSpesa'];

    }

    //genera connessione al PBX
    function getPBXConn($db) {
        $servername = $this->PBX;
        $username = $this->PBXUserName;
        $password = $this->PBXPassword;
        $conn = mysqli_connect($servername,$username,$password,$db) or die("Impossibile connettersi a: ".$db." - ".mysqli_connect_error());
        return $conn;
    }

    //genera connessione ad amanda
    function getProdConn($db) {
        $servername = $this->Amanda;
        $username = $this->AmandaUserName;
        $password = $this->AmandaPassword;
        $conn = mysqli_connect($servername,$username,$password,$db) or die("Impossibile connettersi a: ".$db." - ".mysqli_connect_error());
        return $conn;
    }

    //genera connessione a Siteground
    function getSiteConn() {
        $servername = $this->SiteGround;
        $username = $this->SiteGroundUserName;
        $password = $this->SiteGroundPassword;
        $db = $this->SiteGroundDB;
        $conn = mysqli_connect($servername,$username,$password,$db) or die("Impossibile connettersi a: ".$db." - ".mysqli_connect_error());
        return $conn;
    }

    //genera connessione a eSolver
    function getSistemiConn() {
        $servername = $this->eSolver;
        $username = $this->eSolverUserName;
        $password = $this->eSolverPassword;
        $db = $this->eSolverDB;

        $connection_string = "DRIVER={FreeTDSDom2};SERVER={$servername};DATABASE={$db}";
        $conn = odbc_connect( $connection_string, $username, $password );



        /*$connectionInfo = array( "Database"=>$db, "UID"=>$username, "PWD"=>$password);
        $conn = sqlsrv_connect( $servername, $connectionInfo);*/
        if ($conn) { return $conn; } else { die("Impossibile connettersi a: ".$db." - ". print_r( odbc_errormsg(), true));}
    }

    //genera connessione a Dom2
    function getDom2Conn() {
        $servername = $this->Dom2;
        $username = $this->Dom2UserName;
        $password = $this->Dom2Password;
        $db = $this->Dom2DB;

        /*$connectionInfo = array( "Database"=>$db, "UID"=>$username, "PWD"=>$password);
        $conn = sqlsrv_connect( $servername, $connectionInfo);*/




        $connection_string = "DRIVER={FreeTDSDom2};SERVER={$servername};DATABASE={$db}";
        $conn = odbc_connect( 'MSSQLServer', $username, $password );

        if ($conn) { return $conn; } else { die("Impossibile connettersi a: ".$db." - ". print_r( odbc_errormsg(), true));}
    }

    //distrugge connessione
    function dropConn($conn) {
        mysqli_close($conn);
    }

    //controlla la password da CRM
    function checkPassword($password, $user_hash)
    {
        if(empty($user_hash)) return false;
        if($user_hash[0] != '$' && strlen($user_hash) == 32) {
            return strtolower(md5($password)) == $user_hash;
        }
        return crypt(strtolower(md5($password)), $user_hash) == $user_hash;
    }

    //genera elenco date nel periodo saltando i festivi
    function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while( $current <= $last ) {
            if (date("D", $current) != "Sun" and date("D", $current) != "Sat")
                $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    //genera le option per campo e tabella
    public static function showOpt($conn,$pvalue,$field,$table) {
        $array = $conn->query("SELECT distinct {$field} FROM {$table}");

        $string = "<OPTION value='' SELECTED></OPTION>";
        while ($value = $array->fetch_assoc()) {
            $fvalue = DB::transcode($value[$field]);
            ($value[$field]== $pvalue) ? $selected = 'selected' : $selected = '';
            $string .= "<OPTION value='{$value[$field]}' {$selected}>{$fvalue}</OPTION>";
        }
        return $string;
    }

    //genera date per ripetizione
    public function dateRangeRecurring($first, $last, $days, $step = '+1 day', $format = 'Y-m-d') {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while( $current <= $last ) {

            $needle = date("D", $current);

            if (in_array($needle,$days))
                $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    //genera la stringa sql per selezione fatture, riconoscerle per tipo e generare, se raggruppata, i punti da accreditare
    public static function sqlInvIdenCreds($params,$grouped=true) {
        if ($params['Periodo'] == 'true') {
                                             $datecondition = "AND DataFatturaDA != DataFatturaA";
                                             $calcpoints = "*round(datediff(DataFatturaA,DataFatturaDA)/30,0)";
        }

        if ($grouped) {
                        $maxdate = DB::getInvoicesMaxDate();
                        $grpfields =
                                ",count(esolver_invoices.CodFiscale) as resources, round(datediff(DataFatturaA,DataFatturaDA)/30,0) as months, 
                                ((count(esolver_invoices.CodFiscale)-1)*{$params['RisorseSuccessive']}+{$params['PrimaRisorsa']}){$calcpoints}  as points";
                        $grpcolumn = " GROUP BY esolver_invoices.CodFiscale";
                        $grpjoin = " LEFT JOIN users ON esolver_invoices.codfiscale = users.codfiscale LEFT JOIN esolver_invoices_importstatus ON esolver_invoices.id = esolver_invoices_importstatus.id";
                        $grpwhere = " AND esolver_invoices_importstatus.date > '{$maxdate}'";
                      }  else { $grpfields = ''; $grpcolumn = ''; $grpjoin = '';}
        $sql = "SELECT * {$grpfields}
                FROM esolver_invoices
                {$grpjoin} 
                WHERE ImportoValuta != 0 AND DesEstesa NOT LIKE '%cedolino%' {$datecondition} {$grpwhere}
                {$params['Condizioni']}
                {$grpcolumn}";
        return $sql;
        }

    //recupera i parametri per il calcolo e il riconoscimento delle fatture per tipo (UFFICIO, SEDELEGALE, RECCOMPLPERS, POSTALESTANDARD, OFFICESHARING, HOTDESKINGFT, COWORKING)
    public static function getSQLParams($restype) {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $params = $conn->query("SELECT * FROM credits_schema WHERE restype = '{$restype}'");
        while ($param = $params->fetch_assoc()) {
            $metakey = $param['metakey'];
            $metavalue = $param['value'];
            $sqlparams[$metakey] = $metavalue;
        }
        return $sqlparams;
    }

    //recupera dati dell'utente dall'id di prenotazione
    public static function getUserData($conn,$bookid)
    {
        $data = $conn->query("SELECT * FROM users WHERE bookid = '{$bookid}'")->fetch_assoc();
        ($data['bookingmail'] != '') ? $userdata['email'] = $data['bookingmail'] : $userdata['email'] = $data['crmemail'];
        $userdata['codfiscale'] = $data['codfiscale'];
        $userdata['status'] = $data['status'];
        $userdata['active'] = $data['active'];
        return $userdata;
    }

    //recupera parametro specifico dalla tabella degli schemi di accredito (credits_schema)
    public static function getSchemaParam($conn,$restype,$metakey) {
        $param = $conn->query("SELECT value FROM credits_schema WHERE restype = '{$restype}' AND metakey = '{$metakey}'")->fetch_assoc();
        return $param['value'];
    }

    //traduce in testo leggibile i valori del programma, fa riferimento alla tabella "transcodes"
    public static function transcode($value) {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $transcode = $conn->query("SELECT transcode FROM transcodes WHERE value = '{$value}'")->fetch_assoc();
        if (is_null($transcode['transcode'])) return $value; else return $transcode['transcode'];
    }

    public static function decode($value) {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $transcode = $conn->query("SELECT value FROM transcodes WHERE transcode = '{$value}'")->fetch_assoc();
        if (is_null($transcode['value'])) return $value; else return $transcode['value'];
    }

    //genera sql per visualizzazione tabella filtrata
    public static function sqlTable($table,$field = '',$condition = '' ,$orderfield = '') {
        $sql = "SELECT * FROM {$table}";
        if ($field != '' && $condition != '') {
            $sql .= " WHERE {$field} = '{$condition}'";
        }
        if ($orderfield != '') {
            $sql .= ' ORDER BY '.$orderfield;
        }
        return $sql;
    }

    //calcola il saldo punti dal sito
    public static function pointsBalance($bookid) {

        $db = new DB();
        $conn = $db->getSiteConn();
        if ($bookid != 0) {
            $balance = $conn->query("SELECT sum(points) as total FROM wpsd_wc_points_rewards_user_points WHERE user_id = '{$bookid}' ")->fetch_assoc();
            if (!is_null($balance['total'])) return $balance['total']; else return 0;
        }

        else return 0;

    }

    //aggiorna le opzioni generali
    public static function  saveGeneralOptions($conn,$restype,$periodo,$fedelta,$mesirinnovo,$risorsesuccessive,$primarisorsa) {

        $conn->query("UPDATE credits_schema SET value = '{$periodo}' WHERE restype = '{$restype}' AND metakey = 'Periodo' ");
        $conn->query("UPDATE credits_schema SET value = '{$fedelta}' WHERE restype = '{$restype}' AND metakey = 'Fedelta' ");
        $conn->query("UPDATE credits_schema SET value = '{$mesirinnovo}' WHERE restype = '{$restype}' AND metakey = 'MesiRinnovo' ");
        $conn->query("UPDATE credits_schema SET value = '{$risorsesuccessive}' WHERE restype = '{$restype}' AND metakey = 'RisorseSuccessive' ");
        $conn->query("UPDATE credits_schema SET value = '{$primarisorsa}' WHERE restype = '{$restype}' AND metakey = 'PrimaRisorsa' ");

    }

    //aggiorna le opzioni generali
    public static function  savePromoOptions($conn,$restype,$promoda,$promoa,$bonusperc,$bonuspunti) {

        if ($promoda == '') {
            $conn->query("UPDATE credits_schema SET value = NULL WHERE restype = '{$restype}' AND metakey = 'PromoDa' ");
        } else {
                $promodav = date('Y-m-d',strtotime($promoda));
                $conn->query("UPDATE credits_schema SET value = '{$promodav}' WHERE restype = '{$restype}' AND metakey = 'PromoDa' ");
               }
        if ($promoa == '') {
            $conn->query("UPDATE credits_schema SET value = NULL WHERE restype = '{$restype}' AND metakey = 'PromoA' ");
        } else {$promoav = date('Y-m-d',strtotime($promoa));
                $conn->query("UPDATE credits_schema SET value = '{$promoav}' WHERE restype = '{$restype}' AND metakey = 'PromoA' ");
               }
        $bonusperc = intval($bonusperc);
        $bonuspunti = intval($bonuspunti);

        $conn->query("UPDATE credits_schema SET value = '{$bonusperc}' WHERE restype = '{$restype}' AND metakey = 'PromoValorePercento' ");
        $conn->query("UPDATE credits_schema SET value = '{$bonuspunti}' WHERE restype = '{$restype}' AND metakey = 'PromoValorePunti' ");


    }

    //genera l'array dei punti spendibili per categoria per cliente
    public static function arrayMaxPointsCategory($conn,$bookid) {

        $array = $conn->query("SELECT 
                              bookid,
                              category,
                              company,
                              center,
                              codfiscale,
                              partitaiva,
                              floor(sum(value)) as maxdisc
                      FROM crm_punti.v_charges
                      WHERE bookid = '{$bookid}'
                      GROUP BY category, codfiscale 
                      ORDER BY floor(sum(value)) DESC");

        while ($data = $array->fetch_assoc()) {

            $out[] = $data;

        }

        return $out;
    }

    //cedolini da addebitare: array
    public static function chargesToDo($array,$value)
    {
        for ($i = 0; $i < count($array); $i++) {

            $out[][] = "";

            if ($value <= $array[$i]['maxdisc']) {

                $out[$i]['category'] = $array[$i]['category'];
                $out[$i]['points'] = $value;
                $out[$i]['bookid'] = $array[$i]['bookid'];
                $out[$i]['company'] = $array[$i]['company'];
                $out[$i]['center'] = $array[$i]['center'];

                return $out;

            } else {

                $out[$i]['category'] = $array[$i]['category'];
                $out[$i]['points'] = $array[$i]['maxdisc'];
                $out[$i]['bookid'] = $array[$i]['bookid'];
                $value = $value - $array[$i]['maxdisc'];
                $out[$i]['company'] = $array[$i]['company'];
                $out[$i]['center'] = $array[$i]['center'];
                self::chargesToDo($array, $value);
            }

        }

    }

    //recupera id contratto e id utente per associarli al cedolino automatico
    public static function dom2Ids($bookid)
    {

        $db = new DB();
        $dom2Conn = $db->getDom2Conn();
        $prodConn = $db->getProdConn('crm_punti');

        $dom2Ids = $prodConn->query("SELECT dom2userid FROM v_charges WHERE bookid = '{$bookid}' LIMIT 1")->fetch_assoc();
        $Ids['dom2UserId'] = $dom2Ids['dom2userid'];

        $sql = "
                 SELECT TOP 1 cont_progre FROM [dbo].[loc_contratt]
                 WHERE 
                    rubr_codice = '{$Ids['dom2UserId']}' AND 
                    cont_stato = 'In corso' 
                 ORDER BY cont_progre DESC 
        ";

        $results = odbc_exec($dom2Conn, $sql);

        $rows = array();

        while ($myRow = odbc_fetch_array($results)) { $rows[] = $myRow; }
        foreach ($rows as $row) {
            $Ids['dom2ContractId'] = $row['cont_progre'];
        }
        return $Ids;
        }

    //genera numero di cedolino utilizzabile
    public static function cedolinoId() {

        $db = new DB();
        $dom2Conn = $db->getDom2Conn();

        $sql = "
            SELECT TOP 1 cedo_progre FROM [dbo].[loc_cedolini] ORDER BY cedo_progre DESC
        ";
        $results = odbc_exec($dom2Conn, $sql);

        $rows = array();

        while ($myRow = odbc_fetch_array($results)) { $rows[] = $myRow; }
        foreach ($rows as $row) {
            $cedo_progre = $row['cedo_progre'] + 1;
        }
        return $cedo_progre;

    }

    //prende aliquota IVA da cedolini passati
    public static function dom2IVAData($dom2UserId) {
        $db = new DB();
        $dom2Conn = $db->getDom2Conn();
        $aliquota = array('code'=>'22','value'=>'0.22');
        $sql = "
                 SELECT TOP 1 loc_cedolini.aliq_codice, cast((con_aliquote.aliq_percen/100) as DECIMAL(2,2)) as aliq_value FROM [dbo].[loc_cedolini]
                 LEFT JOIN con_aliquote ON loc_cedolini.aliq_codice = con_aliquote.aliq_codice
                 WHERE 
                    rubr_codice = '{$dom2UserId}' 
                 ORDER BY cedo_progre DESC  
        ";

        $results = odbc_exec($dom2Conn, $sql);

        $rows = array();

        while ($myRow = odbc_fetch_array($results)) { $rows[] = $myRow; }
        foreach ($rows as $row) {
            $aliquota['code'] = $row['aliq_codice'];
            $aliquota['value'] = $row['aliq_value'];
        }
        return $aliquota;
    }

    //recupera il valore più alto della data delle fatture importate per creare gli accrediti
    public static function getInvoicesMaxDate() {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $sql = "SELECT max(date) as maxdate from esolver_invoices_importstatus";
        $date = $conn->query($sql)->fetch_assoc();
        return $maxdate = $date['maxdate'];

    }

}