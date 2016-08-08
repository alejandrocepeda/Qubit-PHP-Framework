<?php
 /*
 * @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
 *  Ayudante para crear elementos link dentro de la seccion head en HTML
 */
 class Qubit_View_Helper_HeadTitle{
    private $_headTitle = '';
    private $_headTitleArray = array();
    private $_headTitleSeparator = ' - ';
    
    
    /*   
     *  @param optional array $data: elementos con sus valores dentro de las etiquetas <link></link>
     *  si no se recibe el parametro $data devuelve un string listo para usar en la seccion html
     *  cuando recibe $data agrega un elemento al arreglo $_headLink y retorna el mismo objeto con $this
     */
    
    public function append($title){
        $this->_headTitleArray[] = $title;
        
        return $this;
    }
    
    public function prepend($title){
         
        array_unshift($this->_headTitleArray, $title);
        
        return $this;
    }
    
    public function separator($sep){
        $this->_headTitleSeparator = $sep;
        
        return $this;
    }
    
    function __toString() {
        
        $output = '';
        foreach($this->_headTitleArray as $key => $item){
            
            if (empty($output)){
                $output.= $item;
            }
            else{
                $output.= $this->_headTitleSeparator . $item;
            }
        }

        $output.=$this->_headTitle;
        return "<title>$output</title>" . "\n";
    }
    
    public function headTitle($title = null){
        
        if (!empty($title)){
            $this->_headTitleArray[] = $title;
        }
        return $this;   
     }
}
