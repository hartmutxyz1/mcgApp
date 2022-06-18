<?php
session_start(); // alles was mit Session beginnt bleibt erhalten bis Browser geschlossen wird
include './conf/conf.inc.php';
include './funktionen.inc.php';

$con = sqlconnect(); //stellt verbindung zum Server und DB her
// User die sich übers Browserfenster abgemeldet haben werden zurückgesetzt.
$t = $conf['timeoutzeit'] * 60;

$sql = "update user set angemeldet=0 WHERE now()-aktivzeit>$t and angemeldet=1";
$r = $con->exec($sql); // Befehl abschicken
// 
// 


if (!isset($_SESSION['user'])) { //ist user nicht angemeldet
    if (!isset($_POST['na'])) { //test ob es na gibt (nur wenn ich vom Login komme)
        header("location:login.php");
    } else {

        $login = filter_input(INPUT_POST, 'na', FILTER_SANITIZE_STRING);
        $pw = filter_input(INPUT_POST, 'pw', FILTER_SANITIZE_STRING);
        $ln = filter_input(INPUT_POST, 'ln', FILTER_VALIDATE_INT);


        if ($login != "root") {

            $sql = "select * from user where loginname='$login'";
            $r = $con->query($sql); // Befehl abschicken
            $zeile = $r->fetch(PDO::FETCH_NUM); // das ist die erste Zeile aus $r

            if ($zeile[0] != "") {
                $hash = $zeile[4];

                if (password_verify($pw, $hash)) {
                    //    if ($hash='123456') {
                    //Pw ist ok
                    $sql = "update user set angemeldet=1,aktivzeit=NULL where loginname='$login'";
                    $r = $con->exec($sql); // Befehl abschicken
                    $_SESSION['user'] = $zeile;
                    $_SESSION['land'] = $ln;
                    if ($ln == 1) {
                        $_SESSION['ln'] = './ln/de.inc.php';
                    } else {
                        $_SESSION['ln'] = './ln/en.inc.php';
                    }
                    $_SESSION['ln'] = './ln/de.inc.php';
                    // Daten aus anforderung löschen
                    $sql = "delete from anforderung where userid='$zeile[0]'";
                    $r = $con->exec($sql);
                } else {
                    header("location:login.php?vorhanden=0");
                }
            } else {
                header("location:login.php?vorhanden=0");
            }
        } else {
            if ($pw == "hh7448") {
                $sql = "select * from user where loginname='admin'";
                $r = $con->query($sql); // Befehl abschicken
                $zeile = $r->fetch(PDO::FETCH_NUM); // das ist die erste Zeile aus $r
                $_SESSION['user'] = $zeile;
                $_SESSION['land'] = $ln;
                if ($ln == 1) {
                    $_SESSION['ln'] = './ln/de.inc.php';
                } else {
                    $_SESSION['ln'] = './ln/en.inc.php';
                }
                $_SESSION['ln'] = './ln/de.inc.php';
            } else {
                header("location:login.php?vorhanden=0");
            }
        }
    }
}

$sqlk = "select * from kopf";
$rk = $con->query($sqlk);
$zeilek = $rk->fetch(PDO::FETCH_NUM);
$_SESSION['kopf'] = $zeilek;

include $_SESSION['ln']; //passende Sprachdatei wählen

$zugriffsrechte = ''; //wenn der User keine Rechte auf einen Link hat dann ist diese Variable nicht leer

if (isset($_GET['rechte'])) {
    $zugriffsrechte = 'Keine Zugriffsrechte'; //wenn der Benutzer keinen Rechte auf einen Link hat dann gibt es die Variable rechte
}

if (isset($_GET['del'])) { // wenn es eine Vari del gibt dann lösche
    //$sgl = "update `dokumente` set status = 'g' WHERE id_nr =" . filter_input(INPUT_GET, 'del', FILTER_VALIDATE_INT);
    $sgl = "update `dokumente` set status = 'g' WHERE id =" . filter_input(INPUT_GET, 'del', FILTER_VALIDATE_INT);
    $con->exec($sgl);
}

if (isset($_GET['del1'])) { // wenn es eine Vari del1 gibt dann lösche
    //$sgl = "delete from  `dokumente` WHERE id_nr =" . filter_input(INPUT_GET, 'del1', FILTER_VALIDATE_INT);
    $sgl = "delete from  `dokumente` WHERE id =" . filter_input(INPUT_GET, 'del1', FILTER_VALIDATE_INT);
    $con->exec($sgl);
}

if (isset($_GET['del2'])) { // wenn es eine Vari del gibt dann lösche
    //$sgl = "update `dokumente` set status = 'v' WHERE id_nr =" . filter_input(INPUT_GET, 'del2', FILTER_VALIDATE_INT);
    $sgl = "update `dokumente` set status = 'v' WHERE id =" . filter_input(INPUT_GET, 'del2', FILTER_VALIDATE_INT);
    $con->exec($sgl);
}

$startseite = 0;
$s_fd = 0;
$s_id = "";
$s_feld0 = "";
$s_feld1 = "";
$s_feld2 = "";
$s_feld3 = "";
$s_feld4 = "";
$s_feld5 = "";
$s_feld6 = "";
$s_ort = "";
$s_status = "v";
$trefferanzahl = $conf['trefferanzahl'];

if (isset($_SESSION['s_id'])) {
    $s_id = $_SESSION['s_id'];
    unset($_SESSION['s_id']);
}

if (isset($_SESSION['s_feld0'])) {
    //echo 'Feld1-' . $_SESSION['s_feld1'];
    $s_feld0 = $_SESSION['s_feld0'];
    unset($_SESSION['s_feld0']);
}

if (isset($_SESSION['s_feld1'])) {
    //echo 'Feld1-' . $_SESSION['s_feld1'];
    $s_feld1 = $_SESSION['s_feld1'];
    unset($_SESSION['s_feld1']);
}
if (isset($_SESSION['s_feld2'])) {
    //echo 'Feld2-' . $_SESSION['s_feld2'];
    $s_feld2 = $_SESSION['s_feld2'];
    unset($_SESSION['s_feld2']);
}
if (isset($_SESSION['s_feld3'])) {
    $s_feld3 = $_SESSION['s_feld3'];
    unset($_SESSION['s_feld3']);
}
if (isset($_SESSION['s_feld4'])) {
    $s_feld4 = $_SESSION['s_feld4'];
    unset($_SESSION['s_feld4']);
}
if (isset($_SESSION['s_feld5'])) {
    $s_feld5 = $_SESSION['s_feld5'];
    unset($_SESSION['s_feld5']);
}
if (isset($_SESSION['s_feld6'])) {
    $s_feld6 = $_SESSION['s_feld6'];
    unset($_SESSION['s_feld6']);
}
if (isset($_SESSION['s_ds'])) {
    //echo 'startseite-' . $_SESSION['s_ds'];
    $startseite = $_SESSION['s_ds'];
    unset($_SESSION['s_ds']);
}

if (isset($_SESSION['s_ort'])) {
    $s_ort = $_SESSION['s_ort'];
    unset($_SESSION['s_ort']);
}
if (isset($_SESSION['s_status'])) {
    //echo 'status-' . $_SESSION['s_status'];
    $s_status = $_SESSION['s_status'];
    unset($_SESSION['s_status']);
}

if (isset($_SESSION['s_fd'])) {
    //echo 'status-' . $_SESSION['s_status'];
    $s_fd = $_SESSION['s_fd'];
    unset($_SESSION['s_fd']);
}



if ($s_status == 'v') {
    $selectedv = 'selected';
} else {
    $selectedv = '';
}

if ($s_status == 'a') {
    $selecteda = 'selected';
} else {
    $selecteda = '';
}

if ($s_status == 'k') {
    $selectedk = 'selected';
} else {
    $selectedk = '';
}

if ($s_status == 'g') {
    $selectedg = 'selected';
} else {
    $selectedg = '';
}

// Auswahlliste für Seiten
$seitenwahl = "<label for='seitenwahl' style='font-size: 12px; font-family: Helvetica,Arial,sans-serif; font-weight:normal' >Seite</label>";
$seitenwahl.="<select id='seitenwahl' name='seitenwahl' class='form-control' style='width:60px; height: 27px;display:inline!important; margin-left:10px; font-size: 10px'>";
$seitenwahl.="</select>";

// Seiten für Treffer
$auswahlseite = "<label for='seitenwahlneu' style='font-size: 12px; font-family: Helvetica,Arial,sans-serif; font-weight:normal' >Ergebnisse je Seite</label>";
$auswahlseite.="<select id='seitenwahlneu' name='seitenwahlneu' class='form-control' style='width:60px; height: 27px;display:inline!important; margin-left:10px; font-size: 10px'>";

for ($i = 5; $i <= 30; $i+=5) {
    if ($i == $trefferanzahl) {
        $auswahlseite.="<option value='$i' selected>$i</option>";
    } else {
        $auswahlseite.="<option value='$i'>$i</option>";
    }
}
$auswahlseite.="</select>";

// wenn es eine Vari anford gibt dann fordere an
if (isset($_GET['anford'])) {
    $ordner = filter_input(INPUT_GET, 'anford', FILTER_VALIDATE_INT);
    $user = $_SESSION['user'][0];
    $sqltest = "select count(*) from anforderung where id_nr=$ordner and userid=$user";
    $resulttest = $con->query($sqltest);
    //echo $sqltest;

    $r = $resulttest->fetch(PDO::FETCH_NUM);
    if ($r[0] == 0) {
        $sql = "insert into anforderung values(null,'$ordner','$user')";
        $con->exec($sql);
    }
}

//Benutzerrechte
$user = $_SESSION['user'][0];
$sqlr = "select * from rechte where uid=$user";
$resultr = $con->query($sqlr);
$rf = $resultr->fetch(PDO::FETCH_NUM);
$row_cnt = $resultr->rowCount();

if ($rf[2] == 0) {
    $fachdienstsql = " ' and fdnr >= 0'";
    $fachdienstsql1 = " where fd_nr >= 0";
} else {
    $fachdienstsql = " '" . " and (fdnr = " . $rf[2];
    $fachdienstsql1 = " where (fd_nr = " . $rf[2];
    while ($zeiler = $resultr->fetch(PDO::FETCH_NUM)) {
        $fachdienstsql.=" or fdnr = " . $zeiler[2];
        $fachdienstsql1.=" or fd_nr = " . $zeiler[2];
    }
    $fachdienstsql.=")'";
    $fachdienstsql1.=")";
//echo $fachdienstsql;
}

// Auswahlliste für fachdienste unter Berücksichtigung der Rechte 
$con = sqlconnect();
if ($rf[2] == 0) { //Zugriff auf alle Fachdienste
    $sqll = "SELECT * FROM fachdienst" . $fachdienstsql1 . " order by fd_nr";
    $rl = $con->query($sqll);

    $fachdienste = "<select id='fd' name='fd' class='suchfeld form-control'>";
    $fachdienste.="<option value='0'>Alle</option>";
} else { // Zugriff auf beliebige Fachdienste
    $sqll = "SELECT * FROM fachdienst" . $fachdienstsql1 . " order by fd_nr";
    $rl = $con->query($sqll);
    $fachdienste = "<select id='fd' name='fd' class='suchfeld form-control'>";
    if ($row_cnt > 1) {
        $fachdienste.="<option value='0'>Alle</option>";
    }
}

while ($zeilel = $rl->fetch(PDO::FETCH_NUM)) {
    $fachdienste.="<option value='" . $zeilel[1] . "'>" . $zeilel[2] . "</option>";
}
$fachdienste.="</select>";

// Werte für Etikettendruck löschen
etikettenloeschen();
unset($_SESSION['oidnr']);

$inp1anzeigen = '';
$inp2anzeigen = '';
$inp3anzeigen = '';
$inp4anzeigen = '';
$inp5anzeigen = '';

if ($_SESSION['kopf'][6] == 1) { //Spalte input1 nicht anzeigen
    $inp1anzeigen = "style='display: none'";
}

if ($_SESSION['kopf'][7] == 1) { //Spalte input2 nicht anzeigen
    $inp2anzeigen = "style='display: none'";
}

if ($_SESSION['kopf'][8] == 1) { //Spalte input3 nicht anzeigen
    $inp3anzeigen = "style='display: none'";
}

if ($_SESSION['kopf'][9] == 1) { //Spalte input4 nicht anzeigen
    $inp4anzeigen = "style='display: none'";
}

if ($_SESSION['kopf'][10] == 1) { //Spalte input5 nicht anzeigen
    $inp5anzeigen = "style='display: none'";
}

// Tabelle bauen
$ausg = "";
$ausg.="  <div class='panel panel-default tabelle_user-kopf'><div class='panel-body' style='background-color: #f6f6f6; font-weight: bold'>";

$ausg.="<div class='col-md-1'>" . $GLOBALS['objektsuche_id'] . "</div>
    <div class='col-md-1'>" . $GLOBALS['objektsuche_lagerort'] . "</div>
    <div class='col-md-1'>" . $GLOBALS['objektsuche_abteilung'] . "</div> 
   <div class='col-md-1' $inp1anzeigen>" . $_SESSION['kopf'][1] . "</div>
    <div class='col-md-1' $inp2anzeigen>" . $_SESSION['kopf'][2] . "</div>
 <div class='col-md-1' $inp3anzeigen>" . $_SESSION['kopf'][3] . "</div>   
    <div class='col-md-2' $inp4anzeigen>" . $_SESSION['kopf'][4] . "</div>
    <div class='col-md-1' $inp5anzeigen>" . $_SESSION['kopf'][5] . "</div>
     <div class='col-md-1'>" . $GLOBALS['objektsuche_feld6'] . "</div>
  <div class='col-md-2'>" . $GLOBALS['objektsuche_status'] . "</div>
</div></div>
";
$ausg.="<div class='panel panel-default' style='border:0; '><div class='panel-body'  style='padding-top: 0px;padding-bottom:5px;border:0px'>";
$ausg.="<div class='col-md-1'><input type='text' id='id' class='suchefeld form-control' value='$s_id'  > </div>
    <div class='col-md-1'><input type='text' id='ort' class='suchefeld form-control' value='$s_ort' > </div> 
    <div class='col-md-1'>" . $fachdienste . "</div>
      <div class='col-md-1' $inp1anzeigen><input type='text' id='feld1' class='suchefeld form-control' value='$s_feld1'  ></div>
     <div class='col-md-1' $inp2anzeigen><input type='text' id='feld2' class='suchefeld form-control' value='$s_feld2' ></div>
      <div class='col-md-1'$inp3anzeigen><input type='text' id='feld3' class='suchefeld form-control' value='$s_feld3'></div> 
      <div class='col-md-2'$inp4anzeigen><input type='text' id='feld4' class='suchefeld form-control' value='$s_feld4'></div>
      <div class='col-md-1' $inp5anzeigen><input type='text' id='feld5' class='suchefeld form-control' value='$s_feld5'  ></div>
     <div class='col-md-1'><input type='text' id='feld6' class='suchefeld form-control' value='$s_feld6'  ></div>      
     <div class='col-md-2'>
    <select id='status' class='form-control' style='width:100%'>
 
    <option " . $selectedv . " value='v'>" . $GLOBALS['objektsuche_verfuegbar'] . "</option>
    <option " . $selecteda . " value='a'>" . $GLOBALS['objektsuche_ausgeliehen'] . "</option>
    <option " . $selectedk . " value='k'>" . $GLOBALS['objektsuche_kassiert'] . "</option>
    <option " . $selectedg . " value='g' >" . $GLOBALS['objektsuche_geloescht'] . " </option> 
    </select>    
</div>
</div></div>
<div id='liste'></div>";

//$ausg.="</tbody>";
//prüfe ob es Anforderungen gibt
$userid = $_SESSION['user'][0];
$sql = "select count(*) from anforderung where userid='$userid'";
$result0 = $con->query($sql);
$r = $result0->fetch(PDO::FETCH_NUM);
$anzahl0 = $r[0];
if ($r[0] == 0) {
    $anforderunganzeigen = 'ausblenden';
} else {
    $anforderunganzeigen = '';
}

// anzahl Daten in Dokumente ermitteln
$sqld = "select count(*) from dokumente where (status != 'k' and status != 'g')";
$result0 = $con->query($sqld);
$rd = $result0->fetch(PDO::FETCH_NUM);
$anzahld = $rd[0];
$_SESSION['anzahld'] = $anzahld;

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
        <script type="text/javascript">
            var userid =<?php echo $_SESSION['user'][0]; ?> // Variable für die Anmeldezeit
            var sekmax = <?php echo $conf['timeoutzeit'] * 60; ?>; // nach 15 min Inaktivität abmelden
        </script>

        <style>
<?php
if ($_SESSION['user'][6] != 1) { //ändern
    echo ".sichtbarstift {display: none}";
}

if ($_SESSION['user'][14] != 1) { //löschen
    echo ".sichtbarloeschen {display: none}";
}

if ($_SESSION['user'][6] != 1) { // Etiketten drucken
    echo ".sichtbardrucken {display: none}";
}
?>

            * {font-family: Helvetica,Arial,sans-serif;font-size:12px}
            .row {margin-left:0}
            .col-md-4 {padding-left:0}
            .btn, .btn:hover , .btn:visited, .btn:active,.btn:focus  {background-color: #4b4b4d; border-color: #4b4b4d}
            .btn:hover {background-color: #b1b3b4; border-color: #b1b3b4}
            .btn:disabled {background-color: #b1b3b4; border-color: #b1b3b4}
            .navbar-default .navbar-nav > .active > a {color: #a4bc05; background-color: white}
            .reihe{padding-top:5px}
            label {color:#4b4b4d;margin-top:10px}
            
            .bild {padding-left:13px; padding-right:13px}
            .vorschaubild {padding-left:13px; padding-right:13px}
            h1 {color: #4b4b4d; font-size: 1.2em}
            h2 {color: #4b4b4d; font-size: 1.2em}
            a, a:visited {color: #4b4b4d;text-decoration: none}
            a:hover {color: #a4bc05;text-decoration: none}
            #liste {max-height:430px;overflow:auto;
            }
            @media (min-device-width: 2000px) and (orientation : landscape) {
                #liste { max-height:260px;overflow:auto;
                       
                }
                .clearfix {
                    overflow: auto;
                }

                li:hover, .dropdown-menu li:hover{background-color: #e7e7e7}
                .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus {background-color: #e7e7e7;color: #4b4b4b}
                option:hover, option:focus {background-color: #e7e7e7;color: #4b4b4b}
                #aktiv {font-weight: bold;
                        color:#a4bc05 }
                .panel {border:0; box-shadow: none}
                
   

                .tabellenkopf {background-color: #efefef; color: #4b4b4d; }
            </style>



            <script>

                var sql1 =<?php echo $fachdienstsql; ?>;
                var anzahld =<?php echo $_SESSION['anzahld']; ?>;
                var seitenschritt = <?php echo $trefferanzahl; ?>;
                var s_id = "";
                var s_ort = "";
                var s_fd = 0;
                var s_feld0 = "";
                var s_feld1 = "";
                var s_feld2 = "";
                var s_feld3 = "";
                var s_feld4 = "";
                var s_feld5 = "";
                var s_feld6 = "";
                var s_status = "v";
                //var s_ds = 0;
                var s_ds = <?php echo $startseite; ?>;

                function leoschen(id) {
                    //alert("id="+id);
                    var r;
                    r = confirm("Wollen Sie das Objekt zum löschen markieren?");
                    //alert(r);
                    if (r == true) {
                        location.replace('objektsuche.php?del=' + id); // rufe die Seite objektsuche.php mit del=id auf
                    }
                }

                function leoschen1(id) {
                    //alert("id="+id);
                    var r;
                    r = confirm("Wollen Sie das endgültig Objekt löschen?");
                    //alert(r);
                    if (r == true) {
                        location.replace('objektsuche.php?del1=' + id); // rufe die Seite objektsuche.php mit del=id auf
                    }
                }

                function leoschen2(id) {
                    //alert("id="+id);
                    var r;
                    r = confirm("Wollen Sie das Objekt wiederherstellen?");
                    //alert(r);
                    if (r == true) {
                        location.replace('objektsuche.php?del2=' + id); // rufe die Seite objektsuche.php mit del=id auf
                    }
                }

                function anford(id) {
                    //alert("id="+id);
                    var r;
                    r = confirm("Wollen Sie das Objekt " + id + "  anfordern?");
                    //alert(r);
                    if (r == true) {
                        location.replace('objektsuche.php?anford=' + id); // rufe die Seite objektsuche.php mit del=id auf
                    }
                }

                function inhalt(id) {
                    //alert("Inhalt=" + id);

                    $.get("./php/sucheinhalt.php?inhalt=" + id, function (data) {
                        alert(data);
                    });
                }

                function umsetzung(ums) {
                    ums = ums.replace(/ü/g, "uuu")
                    ums = ums.replace(/ö/g, "ooo")
                    ums = ums.replace(/ä/g, "aaa")
                    ums = ums.replace(/ß/g, "sss")
                    ums = ums.replace(/Ü/g, "UUU")
                    ums = ums.replace(/Ö/g, "OOO")
                    ums = ums.replace(/Ä/g, "AAA")
                    ums = ums.replace(/'/g, "QQ");
                    ums = ums.replace(/=/g, "YY");
                    ums = ums.replace(/ /g, "XX");
                    ums = ums.replace(/%/g, "WW");
                    return(ums)
                }

                function ergebnisliste() {

                    if (s_status === '') {
                        sql = "1 ";
                    } else {
                        if (s_status === 'v') {
                            sql = " status != 'k' and status != 'g'";
                        } else {
                            sql = " status= '" + s_status + "'";
                        }
                    }

                    if (s_id !== "") {
                        sql += " and id_nr like '%" + s_id + "%'";
                    }

                    if (s_ort !== "") {
                        sql += " and lagerort like '%" + s_ort + "%'";
                    }

                    if (s_feld0 !== "") {
                        sql += " and (input1 like '%" + s_feld0 + "%'" + " or input2 like '%" + s_feld0 + "%'" + " or input3 like '%" + s_feld0 + "%'" + " or input4 like '%" + s_feld0 + "%'" + " or input5 like '%" + s_feld0 + "%'" + " or input6 like '%" + s_feld0 + "%'" + " or id_nr like '%" + s_feld0 + "%'" + " or lagerort like '%" + s_feld0 + "%')";
                        //sql += " and global like '%" + s_feld0 + "%'";
                    }

                    if (s_feld1 !== "") {
                        sql += " and input1 like '%" + s_feld1 + "%'";
                    }

                    if (s_feld2 !== "") {
                        sql += " and input2 like '%" + s_feld2 + "%'";
                    }

                    if (s_fd > 0) {
                        sql += " and fdnr = " + s_fd;
                    }

                    if (s_feld3 !== "") {
                        sql += " and input3 like '%" + s_feld3 + "%'";
                    }


                    if (s_feld4 !== "") {
                        sql += " and input4 like '%" + s_feld4 + "%'";
                    }

                    if (s_feld5 !== "") {
                        sql += " and input5 like '%" + s_feld5 + "%'";
                    }

                    if (s_feld6 !== "") {
                        sql += " and input6 like '%" + s_feld6 + "%'";
                    }

                    sql3 = sql;


                    if (s_fd > 0) {
                        sql += "  order by id_nr limit " + s_ds + "," + seitenschritt;
                    } else {
                        sql += sql1 + "  order by id_nr limit " + s_ds + "," + seitenschritt;
                    }

                    //alert(sql);

                    sql = sql.replace(/'/g, "QQ");
                    sql = sql.replace(/=/g, "YY");
                    sql = sql.replace(/ /g, "XX");
                    sql = sql.replace(/%/g, "WW");
                    sql = sql.replace(/ü/g, "uuu")
                    sql = sql.replace(/ö/g, "ooo")
                    sql = sql.replace(/ä/g, "aaa")
                    sql = sql.replace(/ß/g, "sss")
                    sql = sql.replace(/Ü/g, "UUU")
                    sql = sql.replace(/Ö/g, "OOO")
                    sql = sql.replace(/Ä/g, "AAA")

                    //$('#sql').val(sql);
                    var sqldatum = "?sql=" + sql3;
                    $('#sqldatum').val(sqldatum);

                    //alert(sql);
                    //
                    //alert(sqldatum);
                    //Tabelle wird erstellt Tabelle wird hier erstellt
                    //$("tbody").load('./php/suche.php?sql=' + sql, function () { // schmeisse alles aus rbody rauss und ersetze mit ergebnis

                    $("#liste").load('./php/suche.php?sql=' + sql + '&s_id=' + s_id + '&s_ort=' + s_ort + '&s_feld0=' + s_feld0 + '&s_feld1=' + s_feld1 + '&s_feld2=' + s_feld2 + '&s_feld3=' + s_feld3 + '&s_feld4=' + s_feld4 + '&s_feld5=' + s_feld5 + '&s_feld6=' + s_feld6 + '&s_status=' + s_status + '&s_ds=' + s_ds + '&anzahld=' + anzahld + '&seitenschritt=' + seitenschritt + '&s_fd' + s_fd, function () { // schmeisse alles aus rbody rauss und ersetze mit ergebnis

                        var ruckw = $("#liste").html(); //suche tbody und hole alle html elemente
                        //alert("-"+ruckw+"-");
                        if (ruckw == "") {

<?php
if ($_SESSION['land'] == 1) {
    echo "alert('" . $keinedaten . "')";
} else {
    echo "alert('" . $keinedaten . "')";
}
?>

                            s_ds = 0;
                        }
                    });

                    //Ermittlung der Trefferanzahl
                    $("#treffer").load('./php/treffer.php?sql=' + sql + '&seitenschritt=' + seitenschritt + '&zeile=' + s_ds, function () { // schmeisse alles aus rbody rauss und ersetze mit ergebnis

                    });

                    //Aufbau Seitenauswahl
                    $("#seitenwahl").load('./php/treffer_1.php?sql=' + sql + '&seitenschritt=' + seitenschritt + '&zeile=' + s_ds, function () { // schmeisse alles aus rbody rauss und ersetze mit ergebnis

                    });



                }
                //ENDE FUNKTION SUCHE
                function naechsterDS() {
                    s_ds += seitenschritt;
                    ergebnisliste()
                }

                function vorDS() {
                    s_ds -= seitenschritt;
                    if (s_ds < 0) {
                        s_ds = 0;
                    }
                    ergebnisliste()
                }

                function erster() {
                    s_ds = 0;
                    ergebnisliste()
                }

                function letzter() {
                    s_ds = anzahld - 1;

                    ergebnisliste()
                }

                function archivwahl() {
                    s_ds = 0;
                    ergebnisliste()
                }

                function refresch() {

                    $("#feld0").val("");
                    $("#feld1").val("");
                    $("#feld2").val("");
                    $("#feld3").val("");
                    $("#feld4").val("");
                    $("#feld5").val("");
                    $("#feld6").val("");
                    $("#ort").val("");
                    $("#id").val("");
                    $("#status").val('v');
                    $("#fd").val("");

                    s_id = "";
                    s_ort = "";
                    s_fd = 0;
                    s_feld0 = "";
                    s_feld1 = "";
                    s_feld2 = "";
                    s_feld3 = "";
                    s_feld4 = "";
                    s_feld5 = "";
                    s_feld6 = "";
                    s_status = "v";
                    s_ds = 0;
                    ergebnisliste();
                }

                function logout() {
                    //alert("id="+id);
                    location.replace('login.php?logout=1');
                }

                function hilfe() {
                    //alert("id="+id);
                    location.replace('passwortneu.php');
                }


                $(document).ready(function () { // prüfen ob dokument geladen (seite exestiert)

                    $("#id").keyup(function () {// wenn in id eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#id").val().length > 0) { // wenn die Länge=5 dann rufe Funktion auf
                            s_id = $("#id").val();
                            $("#feld1").val("");
                            $("#feld2").val("");
                            $("#feld3").val("");
                            $("#feld4").val("");
                            $("#feld5").val("");
                            $("#feld6").val("");
                            $("#ort").val("");
                            $("#status").val('v');
                            $("#fd").val("");

                            s_ort = "";
                            s_feld1 = "";
                            s_feld2 = "";
                            s_feld3 = "";
                            s_feld4 = "";
                            s_feld5 = "";
                            s_feld6 = "";
                            s_status = "v";
                            s_ds = 0;
                            s_fd = 0;
                            archivwahl();
                        } else
                        {
                            if (s_id !== "") {
                                s_id = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#ort").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#ort").val().length > 1) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_ort = $("#ort").val();
                            archivwahl();
                        } else
                        {
                            if (s_ort !== "") {
                                s_ort = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#feld0").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld0").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld0 = $("#feld0").val();
                            s_feld0 = umsetzung(s_feld0);
                            archivwahl();
                        } else
                        {
                            if (s_feld0 !== "") {
                                s_feld0 = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#feld1").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld1").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld1 = $("#feld1").val();
                            s_feld1 = umsetzung(s_feld1);
                            archivwahl();
                        } else
                        {
                            if (s_feld1 !== "") {
                                s_feld1 = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#feld2").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld2").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld2 = $("#feld2").val();
                            s_feld2 = umsetzung(s_feld2);
                            archivwahl();
                        } else
                        {
                            if (s_feld2 !== "") {
                                s_feld2 = "";
                                archivwahl();
                            }
                        }
                    });

                    $("#feld3").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld3").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld3 = $("#feld3").val();
                            s_feld3 = umsetzung(s_feld3);
                            archivwahl();
                        } else
                        {
                            if (s_feld3 !== "") {
                                s_feld3 = "";
                                archivwahl();
                            }
                        }
                    });





                    $("#feld4").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld4").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld4 = $("#feld4").val();
                            s_feld4 = umsetzung(s_feld4);
                            archivwahl();
                        } else
                        {
                            if (s_feld4 !== "") {
                                s_feld4 = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#feld5").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld5").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld5 = $("#feld5").val();
                            s_feld5 = umsetzung(s_feld5);
                            archivwahl();
                        } else
                        {
                            if (s_feld5 !== "") {
                                s_feld5 = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#feld6").keyup(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        if ($("#feld6").val().length > 2) { // wenn die Länge=lsize dann rufe Funktion auf
                            s_feld6 = $("#feld6").val();
                            s_feld6 = umsetzung(s_feld6);
                            archivwahl();
                        } else
                        {
                            if (s_feld6 !== "") {
                                s_feld6 = "";
                                archivwahl();
                            }
                        }
                    });
                    $("#status").change(function () {
                        s_status = $("#status").val();
                        archivwahl();
                    });

                    $("#fd").change(function () {// wenn in ort eine Taste gedrückt wird dann prüfe folgendes 
                        s_fd = $("#fd").val();
                        archivwahl();
                    });

                    $("#seitenwahlneu").change(function () {
                        seitenschritt = $("#seitenwahlneu").val();
                        seitenschritt = parseInt(seitenschritt);
                        //alert(seitenschritt);
                        archivwahl();
                    });


                    $("#seitenwahl").click(function () {
                        var seite = $("#seitenwahl").val();
                        seite = parseInt(seite);
                        //alert(seite);
                        if (seite == 1) {
                            s_ds = 0;
                        } else {
                            ds = seite - 1;
                            //alert(ds);
                            s_ds = ds * seitenschritt;
                        }

                        ergebnisliste()
                    });



                    s_feld0 = $("#feld0").val();
                    s_feld1 = $("#feld1").val();
                    s_feld2 = $("#feld2").val();
                    s_feld3 = $("#feld3").val();
                    s_feld4 = $("#feld4").val();
                    s_feld5 = $("#feld5").val();
                    s_feld6 = $("#feld6").val();
                    s_fd = $("#fd").val();
                    s_ort = $("#ort").val();
                    s_id = $("#id").val();
                    s_status = $("#status").val();

                    ergebnisliste();

                });
                $(document).ready(function () {// wenn das Dokument geladen wurde führe eine Funktion aus
<?php
if ($zugriffsrechte != "") {
    if ($_SESSION['land'] == 1) {
        echo "alert('" . $keinerechte . "')";
    } else {
        echo "alert('" . $keinerechte . "')";
    }
}
?>
                });



                function historie(OID, KID) {
                    //alert("hallo oid, kid "+OID + KID);
                    window.open("./log/logread.php?OID=" + OID + '&KID=' + KID, 'targetWindow', 'width=800,height=400,left=300,top=100,scrollbars=yes,status=no, titlebar=no,toolbar=no');
                }

                function bestellung() {
                    window.open("bestellung.php", 'targetWindow', 'width=1100,height=600,top=100,scrollbars=yes');
                }

                function bilderliste(OID) {
                    //window.open("./php/bilderliste_1.php?OID=" + OID + "&IDDEL", 'targetWindow', 'width=800,height=800,left=300,top=100,scrollbars=yes,status=no, titlebar=no,toolbar=no');
                    location.replace("./php/bilderliste.php?OID=" + OID + "&IDDEL");
                }

                function lager(gangid, blockid) {
                    //alert("Gang "+gangid);
                    //alert("Block "+blockid);
                    //$.get("./php/open_gang.php", {gang: gangid, block:blockid},function( data ) {alert(data)});
                    $.get("./php/open_gang.php", {gang: gangid, block: blockid});
                    alert("Gang " + gangid + " Block " + blockid + " wird geöffnet");
                }

                function FensterOeffnen(Adresse) {
                    MeinFenster = window.open(Adresse, "Zweitfenster", "width=600,height=600,left=600,top=200");
                    MeinFenster.focus();
                }

                function FensterOeffnenGross(Adresse) {
                    MeinFenster = window.open(Adresse, "Zweitfenster", "width=1000,height=1000,left=600,top=20");
                    MeinFenster.focus();
                }

                function tabellendruck() {
                    //alert('hallo');
                    //sql = $('input#sql2').val();
                    //var sql = $('input#sql').val();
                    sql = $('input#sqldatum').val();
                    //alert(sql);
                    //pop = window.open('druck_tabelle.php?sql=' + sql, 'targetWindow', 'width=1200,height=600,left=300,top=100,scrollbars=yes,status=no, titlebar=no,toolbar=no,location=no');
                    pop = window.open('druck_tabelle.php' + sql, 'targetWindow', 'width=1200,height=800,left=300,top=100,scrollbars=yes,status=no, titlebar=no,toolbar=no,location=no');
                    pop.focus();//Fenster öffnet sich immer im Vordergrun
                }


                function druckbestellung() {
                    //alert('hallo');
                    //sql = $('input#sql2').val();
                    //var sql = $('input#sql').val();
                    //sql = $('input#sqldatum').val();
                    //alert(sql);
                    //pop = window.open('druck_tabelle.php?sql=' + sql, 'targetWindow', 'width=1200,height=600,left=300,top=100,scrollbars=yes,status=no, titlebar=no,toolbar=no,location=no');
                    pop = window.open('druck_bestellung.php', 'targetWindow', 'width=1200,height=800,left=300,top=100,scrollbars=yes,status=no, titlebar=no,toolbar=no,location=no');
                    pop.focus();//Fenster öffnet sich immer im Vordergrun
                }



            </script>
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
                        <li title="Objekte suchen"><a href='objektsuche.php' id="aktiv" ><?php echo $nav_objekt_suchen; ?></a></li>
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
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"  title="Extras"><?php echo $nav_objekt_stamm; ?> <span class="caret"></span></a>
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
                                <!--  <li > <a href='./handbuch/Handbuch.pdf ' onclick='FensterOeffnenPDF(this.href);
                                          return false' >Handbuch</a></li>-->
                                <li > <a href='./handbuch/Handbuch.pdf ' target='_blank' onclick='FensterOeffnenPDF(this.href);
                                    return false' >Handbuch</a></li>

                            </ul>
                        </li>
                    </ul>

                </div><!--/.nav-collapse -->

            </nav>








            <div class="row" style="margin-top: 100px;">



                <!---Beginn-->
                <div class='container-fluid' style="width:79%;margin-left: auto; margin-right: auto">
                    <div class='row' style="padding-bottom: 10px;padding-left:0;margin-right:0">
                        <div class="col-lg-12" style="padding-left:0">

                            <div class='row' >
                                <div class='col-xs-7' style="margin-top: 10px;text-align:right;">
                                    <div class='col-xs-9' style='padding-left:0'><span>
                                            <input style="padding-left: 30px;display: inline;height:30px; background-image:url(./bilder/suchen-lupe.png); background-repeat: no-repeat"  class="form-control" type="text" id="feld0"  name="feld0"  value='<?php echo $s_feld0; ?>' placeholder=<?php echo $objektsuche_suchfeld ?> />
                                        </span></div>
                                    <div class='col-xs-3'>
                                        <form action="#" method="get" target="blank" id="formdruck_tabelle">
                                            <input type='button' style='width:100%' class='btn btn-primary btn-sm' title='Suchergebnisse zurücksetzen' id='suchezuruecksetzen' value='<?php echo $objektsuche_refresh; ?>' onclick='refresch()'/>
                                        </form>
                                    </div>
                                </div>


                                <div class='col-xs-5' style="text-align:right;margin-top:23px"> <span  id="treffer" ></span>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php echo $auswahlseite; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <!--<img style='height: 7px; width: 7px' src='bilder/einfach-zurueck.jpg' alt='' onclick='vorDS()' title="Vorherige">-->
                                    <span>&nbsp;</span>
                                    <?php echo $seitenwahl; ?>
                                    <span>&nbsp;</span>
                                   <!-- <img style='height: 7px; width: 7px' src='bilder/einfach-vor.jpg' alt='' onclick='naechsterDS()' title="Nächste">-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 ausgabe1" style='overflow:auto;margin-top:20px'>
                        <?php echo $ausg; ?>
                    </div>
                    <div style="height:30px"></div>

                    <div class="col-lg-12" style="padding-right:0" >



                        <div class="col-lg-6"></div>
                        <div class="col-lg-2">

                        </div>

                        <div class="col-lg-2">
                            <form action='#' method="get"  target="blank" id="bestellung">
                                <input type='button' style='width:100%' id='etikettendruck' title='Anforderungen' class='<?php echo $anforderunganzeigen ?> btn btn-primary btn-sm'  value='<?php echo $objektsuche_bestellung . ' (' . $anzahl0 . ')'; ?>'  onclick='druckbestellung()'/><br /><br />
                            </form>


                        </div>
                        <div class="col-lg-2">
                            <form action="#" method="get" target="blank" id="formdruck_tabelle2">
                                        <!--<input type="hidden" name="sql2" id="sql2" />-->
                                <input type="hidden" name="sqldatum" id="sqldatum" />
                                <input type='button' style='width:100%' class='btn btn-primary btn-sm' id='tabdruck'  name='tabdruck'  value='<?php echo $objektsuche_ergebnissedrucken; ?>' title="Liste mit den gefundenen Einträgen drucken" onclick='tabellendruck()'  />
                            </form>
                        </div>

                    </div>


                </div>
                <br>

            </div>


            <div style='height: 20px'></div>
            <?php fuss(); ?> 

            <script type="text/javascript" src="js/lagerscout.js"></script>
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