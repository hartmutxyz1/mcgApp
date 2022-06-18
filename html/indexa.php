<?php
declare(strict_types=1);
//namespace LS;
session_start();
require "../vendor/autoload.php";
if(!isset($_SESSION['Meldung'])){
	$_SESSION['Meldung']='';
}
if(!isset($_SESSION['debugMeldung'])){
	$_SESSION['debugMeldung']='';
}

$request = array_merge($_GET, $_POST);
$seite = (isset($request['s']) ? $request['s'] : 'index');

if (!file_exists('../src/controller/C' . $seite . '.php')) {
	echo '../src/controller/C' . $seite . '.php';
	$seite = 'index';
}
if(!isset($_SESSION['user'])){
	$seite="login"
}
$request['s'] = $seite;
// Controller erstellen
$controllerClassName = 'App\\controller\\C' . $seite ;
//print $controllerClassName;
	
$controller = new $controllerClassName($request);
echo $controller->display();

?>
