<?php

namespace App\controller;

class Cindex {

  private $r = null;
  private $templateName = '';
  private $view;

  public function __construct($request) {
    $this->r = $request;
    $this->templateName = $request['s'] . '.phtml';
    $this->view = new \App\View\ViewPage();
  }

  public function display() {
  

    //$_SESSION['Meldung']="wwwaa";
    //$db = new App\Model\Mysql1Tabelle('rechte_user_entwurf');
    $this->view->setTemplate($this->templateName);
    $info="IndexSeite";
    $this->view->setVars(['info' => $info]); //schickt daten an Templ.
    return $this->view->loadTemplate();
  }

}

?>
