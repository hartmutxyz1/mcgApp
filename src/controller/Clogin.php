<?php

namespace App\controller;

class Clogin {

  private $r = null;
  private $templateName = '';
  private $view;

  public function __construct($request) {
    $this->r = $request;
    $this->templateName = $request['s'] . '.phtml';
    $this->view = new \App\View\ViewPage();
  }

  public function display() {
    unset($_SESSION['teilnehmer']);
    if(isset($_GET['login'])){
      if($_GET['teilnehmer']=="MCG" && ($_GET['passwort']=="2996")){
        $_SESSION['teilnehmer']['name']='MCG';
        header("Location: ./index.php");
        print_r($_SESSION['teilnehmer']);
      }
      else{
        $_SESSION['Meldung']="Diese Daten sind nicht bekannt. Bitte versuche es erneut!";
        //header("Location: ./index.php?s=login");
      }

    }

   
    //$db = new App\Model\Mysql1Tabelle('rechte_user_entwurf');
    $this->view->setTemplate($this->templateName);
    $info="IndexSeite";
    $this->view->setVars(['info' => $info]); 
    
    return $this->view->loadTemplate();
  }

}

?>
