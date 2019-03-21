<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/builder.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/PMSBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/DB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/struct/classes/Log.php';


isset($_GET['bg']) ? $bg = $_GET['bg'] : $bg = 'geobg.png';

isset($_GET['thcolor']) ? $thcolor = $_GET['thcolor'] : $thcolor = 'AF4c50';
isset($_GET['status']) ? $status = $_GET['status'] : $status = '';


$title = 'Modifica file .INI';
builder::startSession();
builder::Header($title,$bg);
builder::Navbar('DataTable');

echo "<p class=\"titolo\">$title</p>";

//carica il file ini e lo parsa
    $filepath = '../points.ini';
    $parsed_ini = parse_ini_file($filepath, true);

    if($_POST){
    $data = $_POST;
    //aggiorna file ini chiama la funzione

        update_ini_file($data, $filepath);
        builder::backToPage('https://punti.pickcenter.com');

    }

    //funzione che aggiorna il file ini
    function update_ini_file($data, $filepath) {
        $content = "";
        //prendo le sezioni usando la funzione di default di PHP
        $parsed_ini = parse_ini_file($filepath, true);

        foreach($data as $section=>$values){
            //aggancio la sezione
            $content .= "[".$section."]".PHP_EOL;
            //aggancio i valori e le chiavi
            foreach($values as $key=>$value){
                $content .= $key." = \"".$value."\"".PHP_EOL;
            }
        }
        //scrivo nel file
        if (!$handle = fopen($filepath, 'w')) {
            return false;
        }
        $success = fwrite($handle, $content);
        fclose($handle);
        return $success;
    }

    $parsed_ini = parse_ini_file($filepath, true);

    echo '
    <div class="tableContainer" style="font-size: small;">
        <table class="table table-sm table-borderless">
            <form class="text-center border border-light p-2" action="" method="post">
            <tr style="text-align: center;" VALIGN="top">
            ';
                foreach($parsed_ini as $section=>$values){
                    echo "
                            
                    <td width='300'><P style='font-weight: bold;background-color: darkred;color: white'>$section</P>";
                    echo "<input class='form-control form-control-sm' type='hidden' value='$section' name='$section' />";
                    foreach($values as $key=>$value){
                        echo "<p><span style='font-weight: bold'>".$key."</span><br><input type='text' class='form-control form-control-sm' name='{$section}[$key]' value='$value' />"."</p>";
                    }
                    echo "</td>";
                }
            echo '
                </tr>
                <tr>
                    <td style="text-align: center" colspan="10"><span style="color: red;font-weight: bold;">Dopo aver aggiornato il file verrete riportati alla schermata di login per attivare le modifiche richieste.</span><br/>
                    <button class="btn btn-warning" type="submit">Aggiorna file INI</button></td>
                </tr>
            </form>
        </table>
    </div>
    
    

';
builder::Scripts();
DB::dropConn($conn);
