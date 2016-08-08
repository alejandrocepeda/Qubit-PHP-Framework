<?php
/** 
 * @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
 *  Ayudante para crear elementos meta dentro de la seccion head en HTML
 *  Modificado: domingo 19 de octubre 2014
 */
 class Qubit_View_Helper_HeadMeta{
    public $_headMeta = array();
    
    /*   
     *  @param optional array $data elementos con sus varoles dentro de las etiquetas <meta></meta>
     *  si no se recibe el parametro $data devuelve un string listo para usar en la seccion html
     *  cuando recibe $data agrega un elemento al arreglo $_headMeta y retorna el mismo objeto con $this
     */
    public function headMeta($data = array(),$options = array()){
        if (empty($data)){  
       
             if (!empty($this->_headMeta)){
                 
                 foreach($this->_headMeta as $item ){
                     $output.=$item; 
                 }
                 
                 return $output;
             }
         }
         else{
            foreach($data as $key => $item){

                $output.= $key . '="' . $item . '" ';   
            }
             
            $html = "<meta $output/>";
             
            if (isset($options['conditional'])){
               $html = '<!--[if ' . $options['conditional'] . ']> ' . $html . '<![endif]-->';    
            }

             $this->_headMeta[] = $html . PHP_EOL;
             return $this;
         }   
     }
}