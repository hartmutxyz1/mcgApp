<?php

namespace App\controller;

class Cuserinp {

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
		$db = new \App\Model\Mysql1Tabelle('user');
		$dialog = new \App\View\Dialog();
		$t=new \App\View\Table();
		$del='concat(\'<button  type="button"  class="tabIcon" data-toggle="modal" data-target="#dialogDel" onclick="clickdialogDel(\',id,\')"><img src="/image/delete.png"  title="Delete"></button>\') As del';                                                                                                                         
		$edit='concat(\'<a href=/s/userInp/\',id,\'">E<a>\') as edit';                                                                                           
		$ds=$db->getDS("id,name,vorname,email,pw,ort,age,$del,$edit");                                                                                           
		$tab=$t->makeTab($ds); 
		//$tab=$t->makeTabSeiten('select ** from adr','id,Ort,age',[],1,5,'');
		$this->view->setTemplate($this->templateName);
		$info="AusgabeSeite";
		$this->view->setVars(['info' => $tab]); //schickt daten an Templ.
		$this->view->setVars(['dialogDel' => $dialog->getDialogYesNo('dialogDel','wirklich löschen','/s/userDel')]); //schickt daten an Templ.
		return $this->view->loadTemplate();
	}

}

?>
