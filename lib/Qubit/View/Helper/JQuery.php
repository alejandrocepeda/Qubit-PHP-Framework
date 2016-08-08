<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para crear escapar texto en la vista depende del setEscape en el View
*/

class Qubit_View_Helper_JQuery{
    private $_jqueryHandler = '$';
    protected $_captureLock;
    private $_onLoadActions = array();
    
    public function onLoadCaptureStart(){

    	if ($this->_captureLock) {
            return false;
        }

    	$this->_captureLock = true;	

    	return ob_start();
    }

    public function onLoadCaptureEnd(){
        $this->_captureLock = false;

        $content = ob_get_clean();

        $this->_onLoadActions[] = $content;  
    }

    public function __toString (){
        
        

        return $this->_render();
    }

    private function quitartag($text,$tag) {
        while(strpos($text,"<".$tag) < strpos($text,"</".$tag.">")) {
            $text = substr($text,0,strpos($text,"<".$tag)).substr($text,strpos($text,"</".$tag.">") + strlen($tag) + 3);
        }
        return $text;
    }

    private function _render(){
        
    	$content = '';

        if (!empty($this->_onLoadActions)) {

            $_isXhtml = Qubit_View::getInstance()->doctype()->isXhtml();

            $content.= '<script type="text/javascript">' . "\n";
            $content.=(($_isXhtml) ? '//<![CDATA[' : '//<!--') . "\n";
            $content.= '$(document).ready(function() {' . "\n";
    	    $content.= implode("\n", $this->_onLoadActions) . "\n";
            $content.= '});' . "\n";
            $content.=(($_isXhtml) ? '//]]>' : '//-->') . "\n";
            $content.= '</script>' . "\n";
        }
    
        return $content;
    }

    public function jquery($data = null){
        
        //if ($data != null){
        //    $this->_onLoadActions[] = $data;
        //}
        return $this;         
    }
}
?>
