<?php
/*
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
*  Ayudante para crear elementos link dentro de la seccion head en HTML
*  Modificado: domingo 19 de octubre 2014
*/

 class Qubit_View_Helper_HeadLink{
    public $_headStyle = array();
    public $_headFiles = array();
    private $_optionsCapture = array();
    protected $_captureLock;

     public function captureStart($options = null){

        if ($this->_captureLock) {
            return false;
        }

        $this->_captureLock = true;
        $this->_optionsCapture = $options;

        return ob_start();
    }

    public function captureEnd(){

        $this->_captureLock = false;

        $content = ob_get_clean();

        $this->_optionsCapture['type'] = 'style';

        $this->appendStyle($content);
    }

    public function appendStyle($content){
        $this->headLink($content,$this->_optionsCapture);
    }

    private function _render(){
        $output.="";

        

        foreach($this->_headFiles as $key => $item){
            $output.=$item; 
        }

        foreach($this->_headStyle as $key => $item){
            $output.=$item; 
        }

        return $output;
    }

    
    public function __toString (){

        return $this->_render();
    }

    /*   
     *  @param optional array $data: elementos con sus valores dentro de las etiquetas <link></link>
     *  si no se recibe el parametro $data devuelve un string listo para usar en la seccion html
     *  cuando recibe $data agrega un elemento al arreglo $_headLink y retorna el mismo objeto con $this
     */
    
    public function headLink($data = null,$options = array()){

        if (!isset($options['type'])){
            $type = 'file';
        }
        else{
            $type = $options['type'];   
        }

        
        if ($type == 'file' and is_array($data)){
            foreach($data as $key => $item){
                $output.= $key . '="' . $item . '" ';   
            }
          
            $html = "<link $output/>";  

            if (isset($options['conditional'])){
               $html = '<!--[if ' . $options['conditional'] . ']> ' . $html . '<![endif]-->';    
            }

            $this->_headFiles[]  = $html . PHP_EOL;
        }
        elseif ($type == 'style' and is_string($data)){

            $html = '<style type="text/css">' . PHP_EOL;
            $html.= $data;
            $html.= '</style>' . PHP_EOL;

            if (isset($options['conditional'])){
                $html = '<!--[if ' . $options['conditional'] . ']> ' . PHP_EOL . $html . '<![endif]-->';          
            }


            $this->_headStyle[] = $html;          
        }
            
        return $this;
     }
}
