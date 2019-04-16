<?php
/**
 * Classe che contiene le funzioni comuni necessarie allo scaricamento delle voci di fattura da eSolver
 * dei cedolini da Dom2 e la loro sincronizzazione con il sistema dei punti sul sito.
 * Ogni funzione è esplicitamente commentata in linea ed ha un'intestazione con la descrizione generale.
 */


class PMSBase
{

//stringa della data in italiano
    public static function DateToItalian($date, $format)
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $italian_days = array('Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $italian_months = array('Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic');
        return str_replace($english_months, $italian_months, str_replace($english_days, $italian_days, date($format, strtotime($date))));
    }

//converte data da italiano a compatibile mysql
    public static function DateItalianToSQL($date, $format)
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $italian_days = array('Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom');
        $english_months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        $italian_months = array('gen', 'feb', 'mar', 'apr', 'mag', 'giu', 'lug', 'ago', 'set', 'ott', 'nov', 'dic');
        return date($format, strtotime(str_replace($italian_months, $english_months, $date)));
    }

//controlla se contatto è nel crm e restituisce le stringhe vero falso
    public static function isCRM($id, $truestring, $falsestring)
    {
        if ($id == '') {
            $rstr = $falsestring;
        } else $rstr = $truestring;
        return $rstr;
    }

//restituisce la data di oggi formattata per mysql
    public static function Now()
    { return $now = (new DateTime('Europe/Rome'))->format('Y-m-d H:i:s'); }

    //stringa casuale
    public static function RandomString($length)
    {
        $string = "";
        $chars = "abcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#*?!_-";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $string .= $chars[rand(0, $size - 1)];
        }
        return $string;
    }

// ---------------------------- FATTURE -----------------------------------------------//

//leggi fatture da eSolver e le sposta nella tabella esolver_invoices
    public static function ReadInvoices()
    {
        $db = new DB;
        $conn_sistemi = $db->getSistemiConn();
        $conn_amanda = $db->getProdConn('crm_punti');
        $errmsg = "";
        $msg = "";

        echo $query = "
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

        ( odbc_error($conn_sistemi) ) ?
        $errmsg = "Impossibile eseguire la query di scarimento delle fatture da esolver" . PHP_EOL . $query . PHP_EOL . "Errore ODBC: " . odbc_errormsg($conn_sistemi) :
        $msg = "Lettura fatture avvenuta correttamente.";


        $invoices = array();
        $logcontent = "";
        $logcounter = 0;
        while($a_invoice = odbc_fetch_array( $invoices_array )){ $invoices[] = $a_invoice; }

        //print_r($invoices);
        



        foreach($invoices as $invoice) {
        //while ($invoice = sqlsrv_fetch_array($invoices_array, SQLSRV_FETCH_ASSOC)) {

            $pk = md5($invoice['IdDocumento'] . $invoice['DesEstesa']);

            //echo $pk  . ' | '. var_dump(self::CheckInvoiceStatus($conn_amanda,$pk));
            //echo '<br>';

            if (!self::CheckInvoiceStatus($conn_amanda, $pk)) {
                $invoice_date_from = self::DateItalianToSQL($invoice['InvoiceDateFrom'], 'Y-m-d');
                $invoice_date_to = self::DateItalianToSQL($invoice['InvoiceDateTo'], 'Y-m-d');
                $value = (int)((float)$invoice['ImportoValuta'] * 100);
                $extdescr = str_replace("'", "\'", $invoice['DesEstesa']); //strip apostrofi
                $socname1 = str_replace("'", "\'", $invoice['RagSoc1']); //strip apostrofi
                $socname2 = str_replace("'", "\'", $invoice['RagSoc2']); //strip apostrofi

                //import invoice

                echo $importsql = "
                INSERT INTO esolver_invoices 
                  (id,IdDocumento,DataFatturaDA,DataFatturaA,ImportoValuta,AnnoCompetenzaIVA,DesEstesa,IdAnagGen,
                  PartitaIVA,CodFiscale,RagSoc1,RagSoc2,IndirEmail)
                VALUES (
                    '{$pk}',
                    '{$invoice['IdDocumento']}',
                    '{$invoice_date_from}',
                    '{$invoice_date_to}',
                    '{$value}',
                    '{$invoice['AnnoCompetenzaIva']}',
                    '{$extdescr}',
                    '{$invoice['IdAnagGen']}',
                    '{$invoice['PartitaIva']}',
                    '{$invoice['CodFiscale']}',
                    '{$socname1}',
                    '{$socname2}',
                    '{$invoice['IndirEmail']}'
                    )";

                $conn_amanda->query($importsql);
                self::SetInvoice($conn_amanda, $pk);

                if ($conn_amanda->error) {

                    $errmsg .= PHP_EOL . "La query: $importsql ha generato errore: $conn_amanda->error";

                } else {
                    // $logcontent .= $importsql . PHP_EOL;
                    ++$logcounter;
                }
            }
        }

        //.log
        $plog = new PickLog();
        $mail = new Mail();

        if ($errmsg == "") {

            Log::wLog($msg);
            $logreturn = "Fatture importate correttamente da eSolver. Righe importate: " . $logcounter;

        } else

        {

            Log::wLog("Errori importazione fatture da eSolver, controllare logs.pickcenter.com.","Errore");
            $smail = $mail->sendErrorEmail($errmsg);
            $msg = $errmsg;

        }

        $params = array(
            'app' => 'PMS',
            'action' => 'ESOLVER_FATTURE_DL',
            'content' => $logreturn,
            'user' => $_SESSION['user_name'],
            'description' => $msg,
            'origin' => 'eSolver.DocUniRigheFattVen, eSolver.DocUniTestata',
            'destination' => 'DBServer.crm_punti.esolver_invoices',
        );
        $plog->sendLog($params);


        return $logreturn;

    }

    public static function CheckInvoiceStatus($conn, $pk)
    {
        $search = $conn->query("SELECT id FROM esolver_invoices_importstatus WHERE id = '{$pk}'");
        if ($search->num_rows == 0) return false; else return true;
    }

    public static function SetInvoice($conn, $pk, $status = 'imported')
    {
        $now = self::Now();
        $conn->query("INSERT INTO esolver_invoices_importstatus (id,date,status) VALUES ('{$pk}','{$now}','{$status}')");
    }

    //recupera la mail associata al booking, e l'id dal CRM. Prima prova a cercare fra le aziende, se non trova cerca fra i contatti
    public static function GetCRMData($codicefiscale)
    {
        $db = new DB();
        $conn = $db->getProdConn('crm');

        $result_account = $conn->query("SELECT id_c, book_email_c, email_account.email_address FROM accounts_cstm 
                                              left join accounts on accounts.id = id_c
                                              left join email_account on accounts.id = email_account.id 
                                              WHERE lead_cf_c = '{$codicefiscale}' and accounts.deleted != 1");

        $account = $result_account->fetch_assoc();
        if ($result_account->num_rows != 0) {
            $crm_data['id'] = $account['id_c'];
            $crm_data['type'] = 'account';
            ($account['book_email_c'] != '') ? $crm_data['crmemail'] = $account['book_email_c'] : $crm_data['crmemail'] = $account['email_address']; //se la mail dedicata è vuota usa la mail primaria

        } else {
            $result_lead = $conn->query("SELECT id_c, book_email_c, email_lead.email_address FROM leads_cstm 
                                                left join leads on leads.id = id_c
                                                left join email_lead on leads.id = email_lead.id
                                                WHERE lead_cf_c = '{$codicefiscale}'");

            $lead = $result_lead->fetch_assoc();
            if ($result_lead->num_rows != 0) {
                $crm_data['id'] = $lead['id_c'];
                $crm_data['type'] = 'lead';
                ($lead['book_email_c'] != '') ? $crm_data['crmemail'] = $lead['book_email_c'] : $crm_data['crmemail'] = $lead['email_address']; //se la mail dedicata è vuota usa la mail primaria
            }
        }
        return $crm_data;
    }

    //aggiorna lo stato delle invoice a seconda del contenuto
    public static function updateInvoicesStatus()
    {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        /*//riporto tutte le fatture a "imported" come in fase iniziale
        $conn->query("UPDATE esolver_invoices_importstatus SET status = 'imported'");*/
        //genero l'array dei sql che generano punti

        $restypes = $conn->query("SELECT distinct(restype) FROM credits_schema");
        while ($restype = $restypes->fetch_assoc()) {
            $sqls[$restype['restype']] = $db->sqlInvIdenCreds($db->getSQLParams($restype['restype']), false);
        }
        foreach ($sqls as $title => $value) {
            $invoiceids = $conn->query($value);
            while ($id = $invoiceids->fetch_assoc()) {
                $conn->query("UPDATE esolver_invoices_importstatus SET status='{$title}' WHERE id = '{$id['id']}'");
            }
        }

        //riconosce le fatture a zero e le segna come tale (status = zero)
        $zeroinvoices = $conn->query("SELECT id FROM esolver_invoices WHERE ImportoValuta = 0");
        while ($zeroinv = $zeroinvoices->fetch_assoc()) {
            $conn->query("UPDATE esolver_invoices_importstatus SET status='ZERO' WHERE id = '{$zeroinv['id']}'");
        }
        //riconosce le fatture manuali non rettificate e le segna come tale (status = manual)
        $manualinvoices = $conn->query("SELECT esolver_invoices.id FROM esolver_invoices
                                              LEFT JOIN esolver_invoices_importstatus ON esolver_invoices.id = esolver_invoices_importstatus.id
                                              WHERE (DesEstesa LIKE '%Uffici %' OR DesEstesa LIKE '%Sedi%' OR DesEstesa LIKE '%Recapiti%') AND esolver_invoices_importstatus.status != 'adjusted'");
        while ($maninv = $manualinvoices->fetch_assoc()) {
            $conn->query("UPDATE esolver_invoices_importstatus SET status='MANUAL' WHERE id = '{$maninv['id']}'");
        }
        //riconosce le fatture per cedolino e le segna come tali (status = receipt)
        $receipts = $conn->query("SELECT id FROM esolver_invoices WHERE DesEstesa LIKE '%cedolino%'");
        while ($rec = $receipts->fetch_assoc()) {
            $conn->query("UPDATE esolver_invoices_importstatus SET status='RECEIPT' WHERE id = '{$rec['id']}'");
        }
    }

    // ---------------------------- UTENTI ED ACCOUNT -----------------------------------------------//

    //controlla se Account esiste già
    public static function CheckUser($conn, $codfis)
    {
        $search = $conn->query("SELECT codfiscale FROM users WHERE codfiscale = '{$codfis}'");
        if ($search->num_rows == 0) return false; else return true;
    }

    //"riconosce" gli account dall'elenco fatture e li genera dentro la tabella users
    public static function CheckCreateUsers()
    {
        $db = new DB();
        $plog = new PickLog();
        $mail = new Mail();
        $conn = $db->getProdConn('crm_punti');
        $logMsg = "";
        $logErrMsg = "";

        $sqlDistinctCF = "SELECT distinct(CodFiscale) FROM esolver_invoices WHERE CodFiscale != ''";
        $datas = $conn->query($sqlDistinctCF);

        //log messages
        ($conn->error) ? $logErrMsg = "Impossibile eseguire la query: " . $sqlDistinctCF . "Errore: " . $conn->error : $logMsg = "Utenti letti correttamente";

        //crea account riconoscendoli dalle fatture

        while ($data = $datas->fetch_assoc()) {

            if (!self::CheckUser($conn, $data['CodFiscale'])) {

                //log skip error
                $accdatas = $conn->query("SELECT CodFiscale,PartitaIva,RagSoc1,RagSoc2 FROM esolver_invoices WHERE CodFiscale = '{$data['CodFiscale']}'")->fetch_assoc();


                $crm_data = self::GetCRMData($data['CodFiscale']);
                $bookdata = self::GetBookData($data['CodFiscale'], $accdatas['PartitaIva']);
                $company = $conn->real_escape_string($accdatas['RagSoc1'] . ' ' . $accdatas['RagSoc2']);
                $crmid = $crm_data['id'];
                $status = self::Status($bookdata['id'], $bookdata['email'], $crmid, $bookdata['active'], $crm_data['crmemail']);

                //log control
                $sqlInsertUser = "INSERT INTO users (codfiscale,partitaiva,company,crmid,bookingmail,bookid,active,status,crmtype,crmemail) VALUES
                ('{$accdatas['CodFiscale']}',
                '{$accdatas['PartitaIva']}',
                '{$company}',
                '{$crmid}',
                '{$bookdata['email']}',
                '{$bookdata['id']}',
                '{$bookdata['active']}',
                '{$status}',
                '{$crm_data['type']}',
                '{$crm_data['crmemail']}'
                )
                ";
                $conn->query($sqlInsertUser);
                ($conn->error) ? $logErrMsg .= "Impossibile eseguire la query:  " . $sqlInsertUser . ". Errore: " . $conn->error : $logMsg = "Aggiornati correttamente " . $datas->num_rows . " utenti";
            }
        }

        if ($logErrMsg == "") $content = $logMsg;
        else {
            $content = $logErrMsg;
            $mail->sendErrorEmail($logErrMsg);
        }

        $params = array(
            'app' => 'PMS',
            'action' => 'CREA_UTENTI_FATTURE',
            'content' => $content,
            'user' => $_SESSION['user_name'],
            'description' => "Controllo e creazione degli utenti da fatture",
            'origin' => 'eSolver.DocUniRigheFattVen, eSolver.DocUniTestata',
            'destination' => 'DBServer.crm_punti.esolver_invoices',
        );
        $plog->sendLog($params);
        Log::wLog($content);

    }

    //aggiorna gli account da sito e da crm
    public static function UpdateUsers()
    {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $datas = $conn->query("SELECT * FROM users");
        while ($data = $datas->fetch_assoc()) {
            $cf = $data['codfiscale'];
            $piva = $data['partitaiva'];
            if ($cf != '') {
                $crm_data = self::GetCRMData($cf);
                $bookdata = self::GetBookData($cf, $piva);
                $crmid = $crm_data['id'];
                $status = self::Status($bookdata['id'], $bookdata['email'], $crmid, $bookdata['active'], $crm_data['crmemail']);
                $conn->query("UPDATE users SET crmid = '{$crmid}',
                                                       crmtype = '{$crm_data['type']}', 
                                                       bookid = '{$bookdata['id']}', 
                                                       bookingmail = '{$bookdata['email']}', 
                                                       active = '{$bookdata['active']}',
                                                       status = '{$status}',
                                                       crmemail = '{$crm_data['crmemail']}'
                                   WHERE codfiscale = '{$cf}'
                        ");
;
            }
        }
    }

    //crea gli utenti online
    public static function createOnlineAccounts()
    {
        $db = new DB();
        $conn = $db->getSiteConn();
        $users = $conn->query("SELECT * FROM users WHERE status = 'tosign'");
        while ($user = $users->fetch_assoc()) {
            ($user['bookingmail'] != '') ? $mail = $user['bookingmail'] : $mail = $user['crmemail'];
            self::AddUser($conn, $user['company'], $mail, $user['codfiscale'], $users['partitaiva'], $db->BPFirstLogin);
            $conn->query("UPDATE users SET status = 'signed' WHERE codfiscale = '{$user['codfiscale']}'");
        }
    }

    //aggiorna lo stato degli account a seconda della completezza dei dati
    public static function Status($id, $mail, $crmid, $active, $crmemail)
    {
        if ($id != 0 && $mail != '' && $crmid != '' && $active == 1) {
            $status = 'active';
        }
        if ($id != 0 && $mail != '' && $crmid != '' && $active == 0) {
            $status = 'signed';
        }
        if ($crmid == '') {
            $status = 'nocf';
        }
        if ($crmid != '' && $mail == '' && $crmemail == '') {
            $status = 'nomail';
        }
        if ($id == 0 && $crmemail != '' && $crmid != '' && $active == 0) {
            $status = 'tosign';
        }
        return $status;
    }

    //utente esiste sul sito
    public static function Exists($conn, $email)
    {
        $check = $conn->query("SELECT id FROM wpsd_users WHERE user_login = '{$email}'")->fetch_assoc();
        $data = $check['id'];
        if ($data == NULL) return false;
        return true;
    }

    //controllo ultimo login di un utente sito/booking
    public static function HasLogged($conn, $bookid)
    {
        $check = $conn->query("SELECT meta_value FROM wpsd_usermeta WHERE meta_key = 'wc_last_active' AND user_id = {$bookid}")->fetch_assoc();
        $datevalue = $check['meta_value'];
        if ($datevalue == NULL) return false;
        return true;
    }

    //inserisce un utente nel sito - primo accesso
    public function AddUser($conn, $name, $email, $cf, $piva, $bonusfirstlogin)
    {
        if (!self::Exists($conn, $email)) {
            $public_password = self::RandomString(8);
            $password = md5($public_password);
            $date = self::Now();
            $conn->query("INSERT INTO wpsd_users (user_login,user_pass,user_nicename,user_email,user_registered,display_name)
                      VALUES ('{$email}','{$password}','{$name}','{$email}','{$date}','{$name}')");
            //prendo l'id appena creato e mi salvo codice fiscale e partita iva
            $lastcreated = $conn->query("SELECT id FROM wpsd_users WHERE user_login = '{$email}'")->fetch_assoc();
            $id = $lastcreated['id'];
            self::AddUserMeta($conn, $id, 'billing_cf', $cf);
            self::AddUserMeta($conn, $id, 'billing_piva', $piva);
            self::AddPoints($id, $bonusfirstlogin, true);

            //invio email al cliente
            $mail = new Mail();
            $newsletterpoints = $mail->newsletterPoints;
            $datamail = $mail->bodyWelcome($name,$bonusfirstlogin,$newsletterpoints,$email,$public_password);
            $body = $mail->mailHeaderFooter($datamail['body']);
            $subject = $datamail['subject'];
            $smail = $mail->sendEmail($email,$name,$subject,$body,$mail->copies);
            Log::wLog("Aggiunto l'utente id: {$id} con email: {$email}", "Inserimento");
        } else Log::wLog("Utente con email: {$email} presente", "Errore inserimento");
    }

    //prende book id e book email dal sito se già registrato
    public static function GetBookData($cf, $piva = '')
    {
        $db = new DB();
        $conn = $db->getSiteConn();
        $book_data = NULL;
        $book_data['active'] = 0;
        ($piva == '') ? $srcpiva = '' : $srcpiva = "OR (meta_key = 'billing_piva' AND meta_value = '{$piva}')"; //controlla se il valore di PIVA è nullo, in caso non lo sia estende la ricerca
        $data = $conn->query("SELECT user_id FROM wpsd_usermeta WHERE (meta_key = 'billing_cf' AND meta_value = '{$cf}') {$srcpiva}  LIMIT 1")->fetch_assoc();
        if ($data['user_id'] != NULL) {
            $book_data['id'] = $data['user_id'];
            self::HasLogged($conn, $data['user_id']) ? $book_data['active'] = 1 : $book_data['active'] = 0;
        }
        $datamail = $conn->query("SELECT user_email FROM wpsd_users WHERE ID = '{$data['user_id']}'")->fetch_assoc();
        if ($datamail['user_email'] != NULL) {
            $book_data['email'] = $datamail['user_email'];
        }
        return $book_data;
    }

    // ---------------------------- AGGIUNTA PUNTI -----------------------------------------------//

    //aggiunge sul sito punti e causale
    public static function AddPoints($bookid, $points, $bypassregistration = false)
    {
        $db = new DB();
        $conn = $db->getSiteConn();
        $date = self::Now();
        if (self::HasLogged($conn, $bookid) || $bypassregistration == true) {
            //aggiungo i punti
            $conn->query("INSERT INTO wpsd_wc_points_rewards_user_points (user_id,points,points_balance,date)
                                VALUES ('{$bookid}','{$points}','{$points}','{$date}')");
            //scrivo il log dei punti
            $conn->query("INSERT INTO wpsd_wc_points_rewards_user_points_log (user_id,points,type,admin_user_id,date)
                                VALUES ('{$bookid}','{$points}','admin-adjustment','4','{$date}')");
            //scrivo log locale
            Log::wLog("Assegnati {$points} punti all'utente con id {$bookid}", "Assegnati punti");
        } else Log::wLog("Mancata assegnazione di {$points} punti all'utente con id {$bookid}", "Utente non attivo");
    }

    //inserisce una meta nel sito per gli utenti
    public static function AddUserMeta($conn, $id, $key, $value)
    {
        $conn->query("INSERT INTO wpsd_usermeta (user_id,meta_key,meta_value) VALUES ('{$id}','{$key}','{$value}')");
    }

    // ---------------------------- ACCREDITI -----------------------------------------------//

    //genera e aggiorna la tabella degli accrediti
    public static function generateCredits() {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        //genero l'array dei sql che generano punti
        $restypes = $conn->query("SELECT distinct(restype) FROM credits_schema");
        while ($restype = $restypes->fetch_assoc()) {
            echo $sqls[$restype['restype']] = $db->sqlInvIdenCreds($db->getSQLParams($restype['restype']), true);
        }
        foreach ($sqls as $title => $value) {

            //echo $title . " | " . $value . "<BR>"; //test
            $invoiceids = $conn->query($value);
            while ($id = $invoiceids->fetch_assoc()) {
                if (!self::creditExists($conn, $id['id'])) { //se l'accredito legato alla fattura già esiste lo lascia come lo trova
                    $now = PMSBase::Now();

                    $status = self::setCreditStatus($id['points'], $id['status'], $id['active']);
                    $points = self::promoPoints($conn,$id['points'],$now,$title); //controlla e calcola le promozioni
                    $conn->query("INSERT INTO credits (invoiceid, bookid, date, points, origin, status, active)
                                    VALUES ('{$id['id']}', '{$id['bookid']}', '{$now}', '{$points}', '{$id['status']}', '{$status}', '{$id['active']}')");

                    //controlla e aggiorna la continuità contrattuale e la crea solo per gli account attivi
                    if ($id['active'] == 1) {
                        Log::wLog("Aggiunto accredito per {$id['codfiscale']}","Accrediti"); //loggo solo gli accrediti pronti ad essere utilizzati
                        PMSBase::calcMonthsContinuity($conn, $id['codfiscale'], $id['status'], $db->CToleranceDays, $now, $id['months']);
                        PMSBase::createAnniversaryBirthdayCredit($conn,$id['codfiscale'],$db->BPAnniversary,$id['bookid']);
                    }
                } else echo $id['id']."<br>";
            }
        }
    }

    //modifica lo stato degli accrediti in base al valore di punti, servizio e attività
    public static function setCreditStatus($points,$origin,$active) {
        $status = 'ready';
        if ($points == 0) $status = 'zero';
        if ($origin == 'manual') $status = 'manual';
        if ($active == 0) $status = 'lost';
        return $status;
    }

    //controllo esistenza accredito legato a quella fattura
    public static function creditExists($conn,$invid) {
        $exists = $conn->query("SELECT id FROM credits WHERE invoiceid = '{$invid}'");
        if ($exists->num_rows == 0) return false; else return true;
    }

    //Check degli accrediti e invio al sito - Zero = accrediti nulli, come coworking, Credited = accrediti assegnati e mandata email
    //sentlost = accrediti a cui è stata inviata mail con esito
    public static function addCreditsToSite() {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        //accrediti da fatture
        $credits = $conn->query("SELECT * FROM v_credits");

        while ($credit = $credits->fetch_assoc()) {
            $status = $credit['status'];
            $mailtosend = new Mail();
            ($credit['bookingmail'] != '') ? $mail = $credit['bookingmail'] : $mail = $credit['crmemail']; //scelgo la mail da usare

            if ($status == 'ready') {
                self::AddPoints($credit['bookid'], $credit['points']);
                $conn->query("UPDATE credits SET status = 'credited' WHERE id = '{$credit['id']}'");
                //invia mail accredito
                $maildata = $mailtosend->bodyCredit($credit['company'],$credit['points']);
                $body = $mailtosend->mailHeaderFooter($maildata['body']);
                $subject = $maildata['subject'];
            }
            if ($status == 'lost') {
                $conn->query("UPDATE credits SET status = 'sentlost' WHERE id = '{$credit['id']}'");
                //invia mail cliente - accredito mancato
                $maildata = $mailtosend->bodyMissed($credit['company']);
                $body = $mailtosend->mailHeaderFooter($maildata['body']);
                $subject = $maildata['subject'];
            }
            $smail = $mailtosend->sendEmail($mail,$credit['company'],$subject,$body,$mailtosend->copies);
        }
        //compleanni e anniversari
        $bdays = $conn->query("SELECT * FROM v_credits_anniversaries");

        while ($bday = $bdays->fetch_assoc()) {
            ($bday['bookingmail'] != '') ? $mail = $bday['bookingmail'] : $mail = $bday['crmemail']; //scelgo la mail da usare
            $bdaydate = strtotime(date('Y-m-d', strtotime($bday['date'])));
            $now = PMSBase::Now();
            $tomorrow = strtotime(date('Y-m-d', strtotime($now . "+ 1 day")));
            if ($bdaydate == $tomorrow) {
                //assegna i punti e aggiorna l'accredito
                self::AddPoints($bday['bookid'], $bday['points']);
                $conn->query("UPDATE credits SET status = 'credited' WHERE id = '{$bday['id']}'");
                //mail e log per auguri di compleanno/anniversario
                if ($bday['origin'] == 'ANNIVERSARY') {
                    $maildata = $mailtosend->bodyAnniversary($bday['company'],$bday['points']);
                    $logtype = "Anniversario";
                }
                if ($bday['origin'] == 'BIRTHDAY') {
                    $maildata = $mailtosend->bodyBirthday($bday['company'],$bday['points']);
                    $logtype = "Compleanno";
                }
                $body = $mailtosend->mailHeaderFooter($maildata['body']);
                $subject = $maildata['subject'];
                $smail = $mailtosend->sendEmail($mail,$bday['company'],$subject,$body,$mailtosend->copies);
                Log::wLog("Accreditato bonus di {$bday['points']} per utente {$bday['bookid']}",$logtype);
            }
        }
    }

    //forza un accredito senza collegarlo automaticamente ad una fattura che però può essere indicata
    public static function forceCredit($conn,$bookid,$date,$points,$origin='BONUS', $status='ready',$note = NULL,$invoiceid='') {
        $active = DB::getUserData($conn,$bookid)['active'];
        $conn->query("INSERT INTO credits (bookid, date, points, origin, status, active, note, invoiceid) VALUES ('{$bookid}','{$date}','{$points}','{$origin}','{$status}','{$active}','{$note}','{$invoiceid}')");
    }

    //---------------------------CONTINUITY----------------------------//

    //continuità contrattuale: controlla ultima fattura e assegna numero di mesi. Aggiorna eventuale continuità già presente.
    public static function calcMonthsContinuity($conn,$cf,$restype,$tolerance,$newinvoicedate,$newperiodmonths) {
        $oldconts = $conn->query("SELECT * FROM continuity WHERE codfiscale = '{$cf}' and restype = '{$restype}'");
        $oldcont = $oldconts->fetch_assoc();

        if ($oldconts->num_rows != 0) {
            $lastinvoicedate = $oldcont['lastinvoice'];
            $oldperiodmonths = $oldcont['periodmonths'];
            $tolerancedate = strtotime("$lastinvoicedate +{$oldcont['periodmonths']} month +{$tolerance} day");
            $checkdate = strtotime($newinvoicedate);

            if ($checkdate <= $tolerancedate) {
                $updateperiodmonths = $oldperiodmonths + $newperiodmonths;
                Log::wLog("Sono stati aggiunti {$newperiodmonths} al cliente {$cf}","Continuità Contrattuale");
            } else {
                    $updateperiodmonths = $newperiodmonths;
                    Log::wLog("Il periodo di continuità è stato resettato per il cliente {$cf}","Continuità Contrattuale");
                    }

            $conn->query("UPDATE continuity SET lastperiodmonths = '{$newperiodmonths}', periodmonths = '{$updateperiodmonths}', lastinvoice = '{$newinvoicedate}' 
                          WHERE id = '{$oldcont['id']}'");

        } else {

            $conn->query("INSERT INTO continuity (codfiscale, firstseen, lastinvoice,periodmonths, bonuscredits, restype, lastperiodmonths) 
                                VALUES ('{$cf}','{$newinvoicedate}','{$newinvoicedate}','{$newperiodmonths}',0,'{$restype}','{$newperiodmonths}')");

        }
    }

    //controlla se la continuità contrattuale può generare un accredito ed in caso lo crea
    public static function generateContinuityCredits(){
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $conts = $conn->query("SELECT continuity.*, users.bookid FROM crm_punti.continuity left join users on continuity.codfiscale = users.codfiscale ");
        while ($cont = $conts->fetch_assoc()) {
            $cycle = DB::getSchemaParam($conn,$cont['restype'],'MesiRinnovo');
            $bonuspoints = DB::getSchemaParam($conn,$cont['restype'],'Fedelta');
            $bonuscreditsnumber = floor($cont['periodmonths']/$cycle)-$cont['bonuscredits'];
            if ($bonuscreditsnumber != 0) {
                $now = PMSBase::Now();
                $totalpoints = $bonuscreditsnumber*$bonuspoints;
                $conn->query("UPDATE continuity SET bonuscredits = '{$bonuscreditsnumber}' WHERE id = '{$cont['id']}'");
                self::forceCredit($conn,$cont['bookid'],$now,$totalpoints,'RENEWAL');
                Log::wLog("Accreditati {$totalpoints} bonus per continutit&agrave; contrattuale ({$cont['restype']}) al cliente con id: {$cont['bookid']}",'Bonus fedelt&agrave;');
                //TODO: mail per accredito bonus
            }
        }
    }

    //crea la continuità contrattuale per gli utenti che non ce l'hanno - non in uso la funzione viene chiamata parametrizzata dentro calcMonthsContinuity
    public static function createContinuities() {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $datas = $conn->query("SELECT codfiscale, min(first_invoice) as first_invoice_date, invoice_status as restype, invoicedate, months
                                     FROM crm_punti.v_continuity 
                                     GROUP BY codfiscale,invoice_status");
        while ($data = $datas->fetch_assoc()) {
            $exists = $conn->query("SELECT codfiscale FROM continuity WHERE codfiscale = '{$data['codfiscale']}' AND restype = '{$data['restype']}'");

            if ($exists->num_rows == 0) {
                $firstseen = date('Y-m-d', strtotime($data['first_invoice_date']));
                $invoice_date = date('Y-m-d', strtotime($data['invoicedate']));
                $conn->query("INSERT INTO continuity (codfiscale, firstseen, lastinvoice,periodmonths, bonuscredits, restype, lastperiodmonths) 
                                VALUES ('{$data['codfiscale']}','{$firstseen}','{$invoice_date}','{$data['months']}',0,'{$data['restype']}','{$data['months']}')");
            }
        }
    }

    //------------------------------------------------ANNIVERSARI e COMPLEANNI------------------------------------------//

    //estrae la data dell'anniversario o del compleanno
    public static function createAnniversaryBirthdayCredit($conn,$cf,$points,$bookid) {
        //genero la data nella variabile $date
        $substrtocheck = substr($cf,0,6);
        $test = strpbrk($substrtocheck, '1234567890') !== FALSE; //contiene numeri?
        $year = date('Y', strtotime(self::Now()));
        if ($test) {
            //azienda seleziona la ricevuta più vecchia importata
            $invoice = $conn->query("SELECT first_invoice FROM v_continuity WHERE codfiscale = '{$cf}' ORDER BY first_invoice limit 1")->fetch_assoc();
            $completedate = $invoice['first_invoice'];
            $timestr =($year+1).'-'.date('m-d',strtotime($completedate));
            $date = date('Y-m-d H:i:s', strtotime($timestr));
            $origin = 'ANNIVERSARY';
        } else {
            //persona fisica decodifica mese e giorno di nascita dal codice fiscale
            $month = self::codfiscMonth(substr($cf, 8, 1));
            //$month = ;
            $day = substr($cf, 9, 2);
            if ($day > 40) $day = $day - 40;
            $timestr = $year . '-' . $month . '-' . $day;
            $date = date('Y-m-d H:i:s', strtotime($timestr));
            $origin = 'BIRTHDAY';
        }

        //Controllo se già non è presente un accredito
        $exist = $conn->query("SELECT id FROM credits WHERE bookid={$bookid} AND origin IN('BIRTHDAY','ANNIVERSARY')")->fetch_assoc();

        //WARNING: gli accrediti di anniversario/compleanno vengono aggiunti con origine "ANNBIRTH" e fattura collegata (invoiceid) NULL
        if ($exist['id'] == NULL) {
            $conn->query("INSERT INTO credits (invoiceid, bookid, date, points, origin, status, active) 
                      VALUES (NULL,'{$bookid}','{$date}','{$points}','{$origin}','ready','1')");
            Log::wLog("Aggiunto un accredito {$origin} per {$cf} alla data {$date}","Accrediti" );
        }

    }

    public static function updAnniversaryBirthdayNewYear() {
        //aggiorna tutti gli anniversari
        //deve essere eseguito in un cronjob il 31/12 per avere la possibilità di fare gli auguri a chi ha compleanno/anniversario il primo dell'anno

        $db = new DB();
        $conn = $db->getProdConn('crm_punti');

        $anniversaries = $conn->query("SELECT id,date,origin FROM credits WHERE origin='ANNBIRTH' AND active = 1");
        while ($ann = $anniversaries->fetch_assoc()) {
            $newdate = date('Y-m-d H:i:s', strtotime($ann['date'] . '+ 1 year'));
            $conn->query("UPDATE credits SET date = '{$newdate}', status = 'ready' WHERE id = '{$ann['id']}'");
        }

        Log::wLog("Tutti gli anniversari\compleanni sono stati aggiornati","Anniversari\Compleanni");

    }

    public static function codfiscMonth($month) {
        switch ($month) {
            case 'A':
                return $out = '01';
                break;
            case 'B':
                return $out = '02';
                break;
            case 'C':
                return $out = '03';
                break;
            case 'D':
                return $out = '04';
                break;
            case 'E':
                return $out = '05';
                break;
            case 'H':
                return $out = '06';
                break;
            case 'L':
                return $out = '07';
                break;
            case 'M':
                return $out = '08';
                break;
            case 'P':
                return $out = '09';
                break;
            case 'R':
                return $out = '10';
                break;
            case 'S':
                return $out = '11';
                break;
            case 'T':
                return $out = '12';
                break;
        }
    }

    //----------------------------- PROMOZIONI ----------------------------------------//

    //aggiorna punti in base a servizio e promo attiva, arrotonda per difetto
    public static function promoPoints($conn,$points,$date,$restype) {

        $from = $conn->query("SELECT metakey, value FROM credits_schema WHERE restype = '{$restype}' and metakey = 'PromoDa' ")->fetch_assoc();
        $to = $conn->query("SELECT metakey, value FROM credits_schema WHERE restype = '{$restype}' and metakey = 'PromoA' ")->fetch_assoc();
        $bonusperc = $conn->query("SELECT metakey, value FROM credits_schema WHERE restype = '{$restype}' and metakey = 'PromoValorePercento' ")->fetch_assoc();
        $bonuspnt = $conn->query("SELECT metakey, value FROM credits_schema WHERE restype = '{$restype}' and metakey = 'PromoValorePunti' ")->fetch_assoc();

        if (is_null($from['value']) && is_null($to['value'])) return $points;
        else {
            $testdate = strtotime($date);
            $fromdate = strtotime($from['value']);
            $todate = strtotime($to['value']);
            if ($testdate >= $fromdate && $testdate <= $todate) {
                $newpoints = floor(($points*(100+$bonusperc['value']))/100 + $bonuspnt['value']);
                return $newpoints;
            } else return $points;
        }
    }


    //-----------------------------------CEDOLINI/ADDEBITI-------------------------------//

    //calcola punti spendibili nel periodo
    public static function calcPoints($cf,$fromdate,$todate) {
        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $perc = $db->percPoints;
        $data = $conn->query("SELECT sum(value) AS total, floor(sum(value)*{$perc}) AS maxpoints 
                              FROM v_charges 
                              WHERE codfiscale = '{$cf}' AND 
                              datestart BETWEEN '{$fromdate}' AND '{$todate}'")->fetch_assoc();
        $output['maxpoints'] = $data['maxpoints'];
        $output['totalvalue'] = $data['total'];
        return $output;

    }

    //legge i cedolini in diretta da Dom2
    public static function readCharges() {
        $db = new DB();
        $connDom2 = $db->getDom2Conn();
        $conn = $db->getProdConn('crm_punti');

        $sql = "SELECT
                        [dbo].[loc_cedolini].cedo_progre as chargenum, 
		                [dbo].[loc_cedolini].cedo_codsoc as center, 
		                [dbo].[loc_cedolini].rise_codice as chargecategory,
		                [dbo].[loc_cedolini].vors_codice as chargeresource, 
		                [dbo].[loc_cedolini].rubr_codice as dom2userid, 
		                [dbo].[loc_cedolini].cedo_datini as chargedatestart,  
		                [dbo].[loc_cedolini].cedo_datfin as chargedateend,  
		                [dbo].[loc_cedolini].cedo_imponi as chargevalue,  
		                [dbo].[std_rubrica].rubr_pariva as userpiva, 
		                [dbo].[std_rubrica].rubr_codfis as usercf, 
		                [dbo].[loc_vocirise].vors_descri  as chargedescription  
		        FROM
                        [domanager2].[dbo].[loc_cedolini]
                LEFT JOIN
                        [dbo].[std_rubrica] on [dbo].[std_rubrica].rubr_codice = [dbo].[loc_cedolini].rubr_codice
                LEFT JOIN
                        [dbo].[loc_vocirise] on ([dbo].[loc_vocirise].rise_codice = [dbo].[loc_cedolini].rise_codice AND [dbo].[loc_vocirise].vors_codice = [dbo].[loc_cedolini].vors_codice)
                WHERE 
		                [dbo].[loc_cedolini].cedo_datreg >= '2019-01-01' AND
                        [dbo].[loc_cedolini].cedo_flgfat != 'Sì' AND
                        [dbo].[loc_cedolini].cedo_imponi > 0 AND		
                        [dbo].[loc_cedolini].rise_codice IN('DAY','FATTORINO','FAX ARRIVO','FAX PART','NOLEGGI','PRENOTAZ','SEGRETERIA','TELEFONICO','ASS TECNIC')";
        $results = odbc_exec($connDom2,$sql);
        $rows = array();

        while($myRow = odbc_fetch_array( $results )){ $rows[] = $myRow; }
        foreach($rows as $row) {
            if(!self::chargeExists($conn,$row['chargenum'])) {
                $datestart = builder::cDateCreate($row['chargedatestart']);
                $dateend = builder::cDateCreate($row['chargedateend']);
                $conn->query("
                              INSERT INTO charges (
                                                    number,
                                                    center, 
                                                    category, 
                                                    source, 
                                                    dom2userid, 
                                                    datestart, 
                                                    dateend, 
                                                    value, 
                                                    partitaiva, 
                                                    codfiscale, 
                                                    description
                                                   )
                              VALUES (
                                                    '{$row['chargenum']}',
                                                    '{$row['center']}',
                                                    '{$row['chargecategory']}',
                                                    '{$row['chargeresource']}',
                                                    '{$row['dom2userid']}',
                                                    '{$datestart}',
                                                    '{$dateend}',
                                                    '{$row['chargevalue']}',
                                                    '{$row['userpiva']}',
                                                    '{$row['usercf']}',
                                                    '{$row['chargedescription']}'
                                                    )
                             ");
            }
        }
    }

    //controlla che il cedolino non sia stato già importato
    public static function chargeExists($conn,$num) {
        $exists = $conn->query("SELECT id FROM charges WHERE number = '{$num}'");
        if ($exists->num_rows == 0) return false; else return true;
    }

    //carica i cedolini sul sito nella tabella wpsd_wc_points_rewards_charges
    public static function uploadCharges() {

        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $siteconn = $db->getSiteConn();

        //tronco la tabella online per i cedolini
        $siteconn->query("TRUNCATE TABLE wpsd_wc_points_rewards_charges");

        //seleziono i cedolini del periodo interessato
        $now = PMSBase::Now();
        $startdate = builder::cDateCreate($now,'Y-m-01');
        $enddate = builder::cDateCreate($now,'Y-m-t');

        $charges = $conn->query("SELECT * FROM v_charges WHERE datestart BETWEEN '{$startdate}' AND '{$enddate}'");

        while ($chr = $charges->fetch_assoc()) {

            $siteconn->query("INSERT INTO wpsd_wc_points_rewards_charges (
                                                id,
                                                number,
                                                center,
                                                category,
                                                source,
                                                dom2userid,
                                                datestart,
                                                dateend,
                                                value,
                                                partitaiva,
                                                codicefiscale,
                                                description,
                                                company,
                                                bookid )
                                    VALUES(
                                                '{$chr['id']}',
                                                '{$chr['number']}',
                                                '{$chr['center']}',
                                                '{$chr['category']}',
                                                '{$chr['source']}',
                                                '{$chr['dom2userid']}',
                                                '{$chr['datestart']}',
                                                '{$chr['dateend']}',
                                                '{$chr['value']}',
                                                '{$chr['partitaiva']}',
                                                '{$chr['codfiscale']}',
                                                '{$chr['description']}',
                                                '{$chr['company']}',
                                                '{$chr['bookid']}'
                                    ) ");
        }

    }

    //scarica le richieste di addebito punti dal sito dalla tabella wpsd_wc_points_rewards_charges_requests
    public static function downloadChargesRequests(){

        $db = new DB();
        $siteConn = $db->getSiteConn();
        $prodConn = $db->getProdConn('crm_punti');

        $dataToDownload = $siteConn->query("SELECT * FROM wpsd_wc_points_rewards_charges_requests WHERE downloaded = 0");

        while ($data = $dataToDownload->fetch_assoc()) {
            $prodConn->query("INSERT INTO debit_requests (date, bookid, points) VALUES ('{$data['date']}','{$data['bookid']}','{$data['points']}')");
        }

        $siteConn->query("UPDATE wpsd_wc_points_rewards_charges_requests SET downloaded = 1"); //segna tutte le richieste come scaricate
    }

    //esegue le richieste di addebito punti
    public static function execChargesRequests() {

        $db = new DB();
        $conn = $db->getProdConn('crm_punti');
        $dom2Conn = $db->getDom2Conn();
        $requests = $conn->query("SELECT bookid, sum(points) as total FROM debit_requests WHERE executed = 0 GROUP BY bookid");

        if ($requests->num_rows != 0) {
            $emailbody = "<p>Cedolini generati dal sistema a PUNTI</p>
                                <table>
                                <tbody>
                                <tr>
                                    <td style=\"border: 1px solid #000000;\">CENTRO</td>
                                    <td style=\"border: 1px solid #000000;\">CLIENTE</td>
                                    <td style=\"border: 1px solid #000000;\">CATEGORIA</td>
                                    <td style=\"border: 1px solid #000000;\">VOCE DOM2</td>
                                    <td style=\"border: 1px solid #000000;\">IMPORTO</td>
                                    <td style=\"border: 1px solid #000000;\">DOM2 USER ID</td>
                                    <td style=\"border: 1px solid #000000;\">DOM2 CONTRACT ID</td>
                                    <td style=\"border: 1px solid #000000;\">CEDOLINO</td>
                                </tr>
                                ";
            $counter = 0;
            while ($req = $requests->fetch_assoc()) {

                $arrayMaxPoints = DB::arrayMaxPointsCategory($conn, $req['bookid']);
                $charges = DB::chargesToDo($arrayMaxPoints, $req['total']);

                $dom2UserId = DB::dom2Ids($req['bookid'])['dom2UserId'];
                $dom2ContractId = DB::dom2Ids($req['bookid'])['dom2ContractId'];



                for ($i=0;$i<count($charges);$i++) {
                    //calcolo e formatto i valori per il cedolino
                    $cedoNum = DB::cedolinoId() + $counter++;
                    $rseDate = (new DateTime())->format('Y-m-d'). " 00:00:00.000";
                    $rseTime = (new DateTime())->format('H:i');
                    $value = $charges[$i]['points'].".000000000";
                    $price = -$charges[$i]['points'].".00";
                    $IVA_code = DB::dom2IVAData($dom2UserId)['code'];
                    $IVA_value = DB::dom2IVAData($dom2UserId)['value'];
                    $taxvalue = $price*$IVA_value;
                    $totalvalue = $price + $taxvalue;
                    $dom2code = DB::transcode($charges[$i]['category']);
                    //preparo il corpo della mail per la notifica (il corpo viene ritornato dalla funzione)
                    $emailbody .= "<tr style='border: 1px solid #000000;'>
                        <td style=\"border: 1px solid #000000;\">{$charges[$i]['center']}</td>
                        <td style=\"border: 1px solid #000000;\">{$charges[$i]['company']}</td>
                        <td style=\"border: 1px solid #000000;\">{$charges[$i]['category']}</td>
                        <td style=\"border: 1px solid #000000;\">{$dom2code}</td>
                        <td style=\"border: 1px solid #000000;\">{$value}</td>
                        <td style=\"border: 1px solid #000000;\">{$dom2UserId}</td>
                        <td style=\"border: 1px solid #000000;\">{$dom2ContractId}</td>
                        <td style=\"border: 1px solid #000000;\">{$cedoNum}</td>
                        </tr>
            ";
                    //preparo le singole SQL da eseguire per inserire i cedolini
                    $sql = "
                    INSERT INTO loc_cedolini ([cedo_progre],
                                              [cedo_codsoc],
                                              [cedo_datreg],
                                              [uten_codice],
                                              [rise_codice],
                                              [rise_codsoc],
                                              [vors_codice],
                                              [rubr_codice],
                                              [rubr_codsoc],
                                              [ruco_progre],
                                              [cont_progre],
                                              [cedo_datini],
                                              [cedo_oraini],
                                              [cedo_datfin],
                                              [cedo_orafin],
                                              [cedo_preuni],
                                              [cedo_quanti],
                                              [cedo_unimis],
                                              [cedo_prezzo],
                                              [cedo_percdu],
                                              [cedo_impodu],
                                              [cedo_percsm],
                                              [cedo_imposm],
                                              [cedo_imponi],
                                              [aliq_codice],
                                              [aliq_codsoc],
                                              [cedo_impost],
                                              [cedo_totale],
                                              [cedo_flgfat],
                                              [fatt_progre],
                                              [cedo_note],
                                              [cedo_testo1],
                                              [cedo_numer1],
                                              [cedo_data1] )
                    VALUES ( '{$cedoNum}',
                             '{$charges[$i]['center']}',
                             '{$rseDate}',
                             'PMS',
                             '{$charges[$i]['category']}',
                             '@@@@',
                             '{$dom2code}',
                             '{$dom2UserId}',
                             '@@@@',
                             NULL,
                             '{$dom2ContractId}',
                             '{$rseDate}',
                             '{$rseTime}',
                             '{$rseDate}',
                             '{$rseTime}',
                             '-1.00',
                             '{$value}',
                             'punti',
                             '{$price}',
                             NULL,
                             NULL,
                             NULL,
                             NULL,
                             '{$price}',
                             '{$IVA_code}',
                             '@@@@',
                             '{$taxvalue}',
                             '{$totalvalue}',
                             'No',
                             NULL,
                             NULL,
                             NULL,
                             NULL,
                             NULL                             
                    )                                             
                    ";
                    odbc_exec($dom2Conn, $sql);
                }


            }
            $emailbody .= "</tbody></table>"; //chiudo il body della mail

            $conn->query("UPDATE debit_requests SET executed = 1 WHERE executed = 0"); //segno tutte le richieste di addebito a 0
            Log::wLog("Aggiunti i cedolini di sconto","Cedolini addebito punti"); //scrivo il log dell'operazione
            return $emailbody; //ritorno il corpo della mail per la notifica
        } else return null; //se non trovo risultati restituisco null
    }



}