<?php include 'session.php'; ?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="mdbootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="mdbootstrap/css/mdb.min.css" rel="stylesheet">
  <link href="mdbootstrap/css/style.css" rel="stylesheet">
  <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="mdbootstrap/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="mdbootstrap/js/popper.min.js"></script>
  <script type="text/javascript" src="mdbootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="mdbootstrap/js/mdb.min.js"></script>
</head>
<nav class="navbar navbar-expand-lg navbar-dark indigo">

      <a class="navbar-brand" href="..\test2.php">WIP - ACS 2.0</a>

    <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Clienti</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\ric_completa.php">BOEZIO</a>
          <a class="dropdown-item" href="..\ric_completa_eur.php">EUR</a>
          <a class="dropdown-item" href="..\ric_completa_reg.php">REGOLO</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dipendenti/Fornitori</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\ric_dipendenti.php">Dipendenti</a>
          <a class="dropdown-item" href="..\ric_dipendenti_swh.php">Dipendenti SmartWorkingHub</a>
          <a class="dropdown-item" href="..\ric_fornitori.php">Fornitori</a>
          <a class="dropdown-item" href="..\ric_pulizie.php">Personale Pulizie</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Report presenze per controllo</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="..\ric_controllo_presenze.php">Dipendenti</a>
          <a class="dropdown-item" href="..\ric_controllo_presenze_fornit.php">Fornitori</a>
          <a class="dropdown-item" href="..\ric_controllo_presenze_mntn.php">Personale Pulizie</a>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">PBX</a>
        <div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="http:\\10.8.0.10\fop2" target="_blank">Apri FOP</a>
          <a class="dropdown-item" href="..\rubrica_fop.php">Rubrica Completa</a>
          <a class="dropdown-item" href="..\fop_dettagli.php">Nuovo Contatto</a>
          <a class="dropdown-item" href="..\fop_pin_replicati.php">PIN e CONTATTI replicati</a>
          <a class="dropdown-item" href="..\fop_elenco_PIN.php">Elenco PIN attivi</a>
        </div>
      </li>
      


      </ul>
      <ul class="navbar-nav mr-auto">

      <li class="nav-item" style="float: right"><a class="nav-link" href="http://crm.pickcenter.com" target="_blank">Smart.Work.CRM</a></li>
      <li class="nav-item"><a class="nav-link" href="mailto:max@swhub.io?subject=Segnalazione A.C.S" target="_blank">Segnala Problemi</a></li>


      <?php if ($_SESSION['user_id'] != NULL) {echo '<li class="nav-item"><a class="nav-link" href="..\..\logout.php" title="Click per logout">'.$_SESSION["user_name"].'</a></li>';} ?>


      </ul>
      </div>

</nav>

