<?php
session_start();

include $_SESSION['ln']; //passende Sprachdatei wählen
include './conf/conf.inc.php';
include './funktionen.inc.php';

if (!isset($_SESSION['user'])) { //ist user nicht angemeldet
    header("location:login.php");
}

if ($_SESSION['user'][9] == 0) { //wenn der Benutzer keine Rechte auf die Seite hat dann rufe objektsuche.php mit rechte=0 auf
    header("location:objektsuche.php?rechte=0");
}



// Werte für Etikettendruck löschen
etikettenloeschen();

$ausgabetab = "";
$meldung = "";


$con = sqlconnect(); //stellt verbindung zum Server und DB her
if (isset($_GET['edit'])) {
    $feld1a = $_GET['feld1'];
    $feld2a = $_GET['feld2'];
    $feld3a = $_GET['feld3'];
    $feld4a = $_GET['feld4'];
    $feld5a = $_GET['feld5'];

    if (isset($_GET['sinp1'])) {
        $usb1 = 1;
    } else {
        $usb1 = 0;
    }

    if (isset($_GET['sinp2'])) {
        $usb2 = 1;
    } else {
        $usb2 = 0;
    }

    if (isset($_GET['sinp3'])) {
        $usb3 = 1;
    } else {
        $usb3 = 0;
    }

    if (isset($_GET['sinp4'])) {
        $usb4 = 1;
    } else {
        $usb4 = 0;
    }

    if (isset($_GET['sinp5'])) {
        $usb5 = 1;
    } else {
        $usb5 = 0;
    }

    $sql = "UPDATE kopf SET inp1='$feld1a',inp2='$feld2a',inp3='$feld3a',inp4='$feld4a',inp5='$feld5a', usb1=$usb1, usb2=$usb2, usb3=$usb3, usb4=$usb4, usb5=$usb5"; // erzeuge den SQL befelh in $sql
    //echo $sql;

    $r = $con->query($sql);
    $meldung = $text_daten_wurden_geandert;
}


$sql = "select * from kopf";
$r = $con->query($sql);
$zeile = $r->fetch(PDO::FETCH_NUM);

$feld1 = $zeile[1];
$feld2 = $zeile[2];
$feld3 = $zeile[3];
$feld4 = $zeile[4];
$feld5 = $zeile[5];
//$feld6 = $zeile[6];

$button = '<input type="submit" id="edit" name="edit"  style="width:100%" class="btn btn-primary btn-sm" value=' . $vorlage_edit . ' />';

$inp1 = '';
$inp2 = '';
$inp3 = '';
$inp4 = '';
$inp5 = '';

if ($zeile[6] == 1) {
    $inp1 = "checked='1'";
}
if ($zeile[7] == 1) {
    $inp2 = "checked='1'";
}
if ($zeile[8] == 1) {
    $inp3 = "checked='1'";
}
if ($zeile[9] == 1) {
    $inp4 = "checked='1'";
}
if ($zeile[10] == 1) {
    $inp5 = "checked='1'";
}

$con = NULL;
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Die 3 Meta-Tags oben *m�ssen* zuerst im head stehen; jeglicher sonstiger head-Inhalt muss *nach* diesen Tags kommen -->
        <meta name="description" content="">
        <meta name="author" content="Z&ouml;llner B&uuml;ro- &amp; IT-Systeme GmbH">

        <title>LagerScout</title>
        <link rel="Shortcut Icon" href="favicon.ico">
        <!-- Bootstrap-CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="js/jquery-ui.min.css">

        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>

        <script src="js/jquery-ui.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="css/screen.css" />
        <!-- Besondere Stile f�r diese Vorlage -->
        <link href="css/navbar-fixed-top.css" rel="stylesheet">
        <!-- Unterst�tzung f�r Media Queries und HTML5-Elemente in IE8 �ber HTML5 shim und Respond.js -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
       <![endif]-->
        <script>

            var userid =<?php echo $_SESSION['user'][0]; ?> // Variable für die Anmeldezeit
            var sekmax = <?php echo $conf['timeoutzeit'] * 60; ?>; // nach 15 min Inaktivität abmelden

            function logout() {
            location.replace('login.php?logout=1');
            }
            // wenn das Dokument geladen wurde führe eine Funktion aus
            $(document).ready(function () {

<?php
if ($meldung != "") {
    echo "alert('$meldung')";
}
?>

            });


        </script>

        <style>
            * {font-family: Helvetica,Arial,sans-serif;font-size:12px}
            .row {margin-left:0}
            .col-md-4 {padding-left:0}
            .btn, .btn:hover , .btn:visited, .btn:active,.btn:focus  {background-color: #4b4b4d; border-color: #4b4b4d}
            .btn:hover {background-color: #b1b3b4; border-color: #b1b3b4}
            .btn:disabled {background-color: #b1b3b4; border-color: #b1b3b4}
            .navbar-default .navbar-nav > .active > a {color: #a4bc05; background-color: white}
            .reihe{padding-top:5px}
            label {color:#4b4b4d;margin-top:10px}

            .kopfabstand {padding-bottom:20px}
            h1 {color: #a4bc05; font-size: 1.2em;margin-top:0;}
            h2 {color: #4b4b4d; font-size: 1.2em}
            a, a:visited {color: #4b4b4d;text-decoration: none}
            a:hover {color: #a4bc05;text-decoration: none}
            .clearfix {
                overflow: auto;
            }

            li:hover, .dropdown-menu li:hover{background-color: #e7e7e7}
            .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {background-color: #e7e7e7;color: #4b4b4b}
            option:hover, option:focus {background-color: #e7e7e7;color: #4b4b4b}
            #aktiv {font-weight: bold;
                    color:#a4bc05 }

        </style>

    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" style="margin-top:0px; background-color: #4b4b4d;" >
            <div class="row">
                <div class="col-lg-9" >
                </div>
                <div class="col-lg-3 clearfix timeout" style="color:#a4bc05; font-size:0.9em; margin-top:20px; text-align:right">

                    <div class="col-xs-10">
                        <span style="padding-right:15px">Logout in <span id="timeout"></span> Minuten </span>
                    </div>
                    <div class="col-xs-2">
                        <a href="JavaScript: logout()"><img src="bilder/logout-icon.png" alt="Abmelden" title="Abmelden" width="15"  style="float: right;margin-right:30px"> </a>
                    </div>

                </div>
            </div>
        </nav>
        <!-- Fixierte Navbar -->
        <nav class="navbar navbar-default navbar-fixed-top" style="background-color:white;margin-top:50px" >
            <div class="navbar-header">
                <a href="objektsuche.php?start" title="Zur Startseite"> <img src="bilder/lager-scout-logo.png" alt='' style="width:120px; padding-bottom:10px;padding-top:20px;margin-left: 50px" ></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Navigation ein-/ausblenden</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-collapse collapse"  >
                <ul class="nav navbar-nav navbar-left" style="margin-left: 30px;margin-top:25px;">
                    <li title="Objekte suchen"><a href='objektsuche.php' ><?php echo $nav_objekt_suchen; ?></a></li>
                    <li title="Objekte anlegen & bearbeiten"><a href='erfassen.php' ><?php echo $nav_objekt_erfassen; ?></a></li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"  title="Objektbewegung"><?php echo $nav_objekt_bewegen; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href='zuordnen.php' ><?php echo $nav_lagerort_zuordnen; ?></a></li>
                            <li><a href='entnahme.php' ><?php echo $nav_entnahme; ?></a></li>
                            <li><a href='ausgeliehen.php' ><?php echo $nav_objekt_ausgeliehen; ?></a></li>
                            <li><a href='rueckgabe.php' ><?php echo $nav_rueckgabe; ?></a></li>
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"  title="Extras">Kassation <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href='kassantrag.php' ><?php echo $nav_kassation_antrag; ?></a></li>
                            <li><a href='kassation.php' ><?php echo $nav_kassation; ?></a></li>

                        </ul>
                    </li>
                    <li><a href='abliefnachweis.php'><?php echo $nav_abliefnachweis; ?></a></li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" id="aktiv" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"  title="Extras"><?php echo $nav_objekt_stamm; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href='user.php' ><?php echo $nav_objekt_user; ?></a></li>
                            <li><a href='lager.php' ><?php echo $nav_objekt_lager; ?></a></li>
                            <li><a href='kopf.php' ><?php echo $nav_objekt_kopf; ?></a></li>
                            <li><a href='fachdienst.php'  ><?php echo $nav_objekt_fachdienst; ?></a></li>
                           
                        </ul>
                    </li>
                    <li class="dropdown" >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"  title="Extras">Extras <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li ><a href="etikettendruck.php"><?php echo $objektsuche_etikettendruck; ?></a></li>
                            <?php
                            if ($conf['auto_id'] == 0) {
                                echo '<li ><a href="etikettendruck_blanco.php">Etikettendruck blanco</a></li>';
                            }
                            ?>

                                
                                <li ><a href="passwortneu.php" title="Passwort neu" >Passwort ändern</a></li>
                              <li > <a href='./handbuch/Handbuch.pdf ' target='_blank' onclick='FensterOeffnenPDF(this.href);
                                        return false' >Handbuch</a></li>

                        </ul>
                    </li>
                </ul>

            </div><!--/.nav-collapse -->

        </nav>
        <!-- Content  -->
        <div class='container-fluid' style="width:79%;margin-left: auto; margin-right: auto">
            <div class="row" style="margin-top: 100px;"> <div class="col-lg-2" >  <h1>Stammdaten | Kopfdaten</h1></div></div>

            <form action="#" method="get" id="formErfassen">
                <div class="row">
                    <div class='col-md-6'>  
                        <h2><?php echo $kopf_spaltentexttext; ?></h2>
                    </div>
                    <div class='col-md-2' style="text-align:center">
                        <h2><?php echo $kopf_spalteunsichtbar; ?></h2>
                    </div>
                </div>
                <div class="row  kopfabstand">
                    <div class='col-sm-6' style="text-align:center">  
                        <input type="text" name="feld1" id="feld1" value="<?php echo $feld1 ?>" class="form-control" placeholder="Spalte1"  />
                    </div>
                    <div class='col-sm-2' style="text-align:center">
                        <input type="checkbox" name="sinp1" id="sinp1"  <?php echo $inp1 ?> />
                    </div>
                </div>
                <div class="row  kopfabstand">
                    <div class='col-sm-6'>  
                        <input type="text" name="feld2" id="feld2" value="<?php echo $feld2 ?>" class="form-control" placeholder="Spalte2"/>
                    </div>
                    <div class='col-sm-2' style="text-align:center">
                        <input type="checkbox"  name="sinp2" id="sinp2" <?php echo $inp2 ?> />
                    </div>
                </div>
                <div class="row  kopfabstand">
                    <div class='col-sm-6'>  
                        <input type="text" name="feld3" id="feld3" value="<?php echo $feld3 ?>" class="form-control" placeholder="Spalte3" />
                    </div>
                    <div class='col-sm-2 ' style="text-align:center"> 
                        <input type="checkbox" name="sinp3" id="sinp3" <?php echo $inp3 ?> />
                    </div>
                </div>
                <div class="row  kopfabstand">
                    <div class='col-sm-6'>  
                        <input type="text" name="feld4" id="feld4" value="<?php echo $feld4 ?>" class="form-control" placeholder="Spalte4" />
                    </div>
                    <div class='col-sm-2' style="text-align:center">  
                        <input type="checkbox" name="sinp4" id="sinp4" <?php echo $inp4 ?> />
                    </div></div>
                <div class="row  kopfabstand">

                    <div class='col-sm-6'>  
                        <input type="text" name="feld5" id="feld5" value="<?php echo $feld5 ?>" class="form-control" placeholder="Spalte5"/>
                    </div>
                    <div class='col-sm-2 ' style="text-align:center">  
                        <input type="checkbox" name="sinp5" id="sinp5" <?php echo $inp5 ?> />
                    </div>

                    <div class='col-sm-2' style="text-align: right; padding-right: 30px" >
                        <?php echo $button; ?> 
                    </div>
                </div>  
            </form>

        </div>

        <?php fuss(); ?>

        <script type="text/javascript" src="js/jquery.fixedheadertable.min.js"></script>
        <script> $('#tabelle').fixedHeaderTable({footer: false, cloneHeadToFoot: false, fixedColumn: false});</script>

        <!-- Bootstrap-JavaScript
                          ================================================== -->
        <!-- Am Ende des Dokuments platziert, damit Seiten schneller laden -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- IE10-Anzeigefenster-Hack für Fehler auf Surface und Desktop-Windows-8 -->
        <!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>-->
        <script type="text/javascript" src="js/lagerscout.js"></script>
    </body>
</html>
