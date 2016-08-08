<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
* ayudante para setear el doctype
*/

class Qubit_View_Helper_Doctype{
    
    
   private $_doctype = null;
   private $_registry = array();
   private $_defaultDoctype = 'HTML4_LOOSE';

    public function __construct(){
        
        $this->_registry = array(
            XHTML11 => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
            XHTML1_STRICT => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
            XHTML1_TRANSITIONAL => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            XHTML1_FRAMESET => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
            XHTML1_RDFA => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">',
            XHTML1_RDFA11 => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">',
            XHTML_BASIC1 => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.0//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd">',
            XHTML5 => '<!DOCTYPE html>',
            HTML4_STRICT => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
            HTML4_LOOSE => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
            HTML4_FRAMESET => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
            HTML5 => '<!DOCTYPE html>'
        );
        
    }

    public function isXhtml(){
        return (stristr($this->getDoctype(), 'xhtml') ? true : false);
    }

    public function getDoctype(){

        if (array_key_exists($this->_doctype, $this->_registry)) {
            return $this->_registry[$this->_doctype] . "\n";
        }
        else{
            return $this->_registry[$this->_defaultDoctype] . "\n";
        }
    }
    
    public function __toString(){

        return $this->getDoctype();    
    }
   
    /*   
    *  @param optional array $doctype: recibe  
    */
    public function doctype($doctype = null){
        
        if ($doctype != null) {
            $this->_doctype = $doctype; 
        }

        return $this;
    }
}
?>