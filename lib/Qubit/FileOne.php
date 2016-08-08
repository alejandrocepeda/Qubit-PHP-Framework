<?php
/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */
class Qubit_FileOne {
    private $_file = array();
    private $_destination = null;
    private $_filename = null;
    
    private $_options = array();
    
    public function getDestination(){ 
        return $this->_destination ;
    }
    
    public function addValidation($options){
        $this->_options = $options;
    }
    
    
    
    public function setDestination($destination,$filename = null){
        $destination = rtrim($destination, "/\\");
        $this->_filename = $filename;
         if (!is_dir($destination)) {
             throw new Qubit_Exception('El destino especificado no es un directorio o no existe');
         }
         
         if (!is_writable($destination)) {
             throw new Qubit_Exception('No se puede escribir en el destino especificado');
         }
         
         $this->_destination = $destination . '/';
         
         return $this;
    }
    

    private function _toByteString($size){
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        for ($i=0; $size >= 1024 && $i < 9; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . $sizes[$i];
    }
    
    public function getFileSize($file){
        
         if (is_array($file)){
            $size = $file['size'];
        }
        else{
            $size = $this->_file[$file]['size'];
        }
        
        return $this->_toByteString($size); 
    }
    
    public function getErrorUpload($file){
        
        return $this->_file[$file]['error'];
    }
    
    public function isFile($file){
    
        if (is_array($file)){
            $str = strlen($file['tmp_name']);
        }
        else{
            $str = strlen($this->_file[$file]['tmp_name']);
        }
        return ($str > 0) ? true: false; 
    }

    public function toArray(){
        
        return $this->_file;
    }
    public function getFileName($file){
         if (is_array($file)){
            return $file['name'];
        }
        else{
            return $this->_file[$file]['name'];
        }
    }
    
    public function getMimeType($file){
        if (is_array($file)){
            return $file['type'];
        }
        else{
            return $this->_file[$file]['type'];
        }
    }
    
    function __construct() {
        $this->_file = $_FILES;
    }


    public function getFiles($file,$multi = false){
        
        $fdata = $_FILES[$file];
        
        if($multi){
            for ($i = 0; $i < count($fdata['name']); ++$i) {
                $files[] = array(
                    'name' => $fdata['name'][$i],
                    'tmp_name' => $fdata['tmp_name'][$i],
                    'type' => $fdata['type'][$i],
                    'size' => $fdata['size'][$i],
                );
            }
            
            return $files;
        }
        else{
            return $_FILES[$file]; 
        }
    }
    
    public function Upload($file,$createdir = false){
        
        $destination = $this->_destination;
        
        if (is_array($file)){
            $filesize = $file['size'];
            $filetype = $file['type'];
            $name= $file['name'];
            $tmp_name = $file['tmp_name'];
        }
        else{
            $filesize = $_FILES[$file]['size'];
            $filetype = $_FILES[$file]['type'];
            $name= $_FILES[$file]['name'];
            $tmp_name = $_FILES[$file]['tmp_name'];
        }
        
	if ( $filesize > 0){  
	
            $ruta_fichero = $destination;

            if ($createdir){
                if (!is_dir("$ruta_fichero")){
                    mkdir("$ruta_fichero",0777);    
                }
            }

            $out = false;
            
            if ($this->_filename == null){
                $path_destination = $ruta_fichero.$name;
            }
            else{
                $path_destination = $ruta_fichero.$this->_filename;
            }
            
            if (copy ($tmp_name,$path_destination)){
                $out = true;
            }          
            unlink($tmp_name);           

            return $out;
	}
	else
	{
            return false;
	}
    }
}
?>
