<?php

namespace App\View;

class ViewPage {

    private $path = '../src/Template';
    private $template = 'index.phtml';
    private $v;    //Variablen im Tempate

    public function __construct() {
    }
    
    function setVars(array $vars) {
        foreach ($vars as $key => $val) {
            $this->v[$key] = $val;
        }
    }

    public function setTemplate($template = 'index.phtml') {
        $this->template = $template;
    }

    public function loadTemplate() {
        $v=&$this->v;           //Referenz auf Übergabevar.
        $file = $this->path . DIRECTORY_SEPARATOR . $this->template;
        if (file_exists($file)) {
            ob_start();
            include $this->path . DIRECTORY_SEPARATOR . 'kopf.phtml';
            include $file;
            include $this->path . DIRECTORY_SEPARATOR . 'fuss.phtml';
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        } else {
            \App\Helper\Meldung::p('kein Template: '.$file,'F#0001viewp');
            exit();
        }
    }
}

?>
