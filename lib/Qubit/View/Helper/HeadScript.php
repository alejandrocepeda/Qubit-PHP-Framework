<?php
/** 
 * @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
 *  Ayudante para crear elementos scripts dentro de la seccion head en HTML
 *  Modificado: domingo 19 de octubre 2014
 */

 class Qubit_View_Helper_HeadScript{
    public $_headScript = '';
    public $_headFiles = array();
    protected $_captureLock;
    
    /*
     * recorna la direccion del script con un parametro de microtime para evitar cache Ej: js/jquery.min.js?v=256151515
     */
    private function last_version($file_name){ 
        if (file_exists($file_name)){
            return $file_name."?v=".filemtime($file_name); 
        }   
    }
        
    public function captureStart(){

        if ($this->_captureLock) {
            return false;
        }

        $this->_captureLock = true;

        return ob_start();
    }

    public function captureEnd(){

        $this->_captureLock = false;

        $content = ob_get_clean();
        $this->createData($content);
    }
    
    private function _render(){
        foreach($this->_headFiles as $key => $item){
            $output.=$item; 
        }

        $output.= PHP_EOL;

        if (!empty($this->_headScript)) {

            $_isXhtml = Qubit_View::getInstance()->doctype()->isXhtml();

            $output.= '<script type="text/javascript">' . PHP_EOL;
            $output.=(($_isXhtml) ? '//<![CDATA[' : '//<!--') . PHP_EOL;
            $output.= implode(PHP_EOL, $this->_headScript) . PHP_EOL;
            $output.=(($_isXhtml) ? '//]]>' : '//-->') . PHP_EOL;
            $output.= '</script>' . PHP_EOL;
        }

        return $output;    
    }

    public function __toString(){
        return $this->_render();
    }

    
    public function createData($content){
        $this->headScript($content,array(type => 'script'));
    }
    

     /*   
     *  @param optional array $data: elementos con sus valores dentro de las etiquetas <link></link>
     *  @param optional boolean $version: para usar la function last_version() para evitar cache de navegador
     *  si no se recibe el parametro $data devuelve un string listo para usar en la seccion html
     *  cuando recibe $data agrega un elemento al arreglo $_headScript y retorna el mismo objeto con $this
     */
    
    public function headScript($data = null, $options = array()){

        if (!isset($options['type'])){
            $type = 'file';
        }
        else{
            $type = $options['type'];   
        }

        if (!isset($options['version'])){
            $version = false;
        }
        else{
            $version = $options['version'];   
        }


        if ($type == 'file' and is_array($data)){
            foreach($data as $key => $item){
                if ($key == 'src' and $version){
                    $item = $this->last_version($item);
                }
                $output.= $key . '="' . $item . '" ';   
            }
            
            $html = "<script $output></script>";  

            if (isset($options['conditional'])){
               $html = '<!--[if ' . $options['conditional'] . ']> ' . $html . '<![endif]-->';    
            }
            
            $this->_headFiles[]  = $html . PHP_EOL;
        }
        elseif ($type == 'script' and is_string($data)){
            $this->_headScript[] = $data . PHP_EOL;          
        }
            
        return $this;
    }
}
?>