<?php

/**
 *
 * @author Alejandro Cepeda, alejandrocepeda25@gmail.com
 */
class Qubit_View_Helper_FileInfo{
      
    private $_info = array();
    
    private function _getImageSize($file){
        $data = getimagesize($file);
        $out['width'] = $data[0]; 
        $out['height'] = $data[1]; 
        $out['dimensions'] = $data[3]; 
        
        return $out;
    }
    
    

    public function fileinfo($file){
        
        $values = $this->_getImageSize($file);
        
        $this->_info['dimensions'] = $values['dimensions'];
        $this->_info['width'] = $values['width'];
        $this->_info['height'] = $values['height']; 
        $this->_info['time'] = filemtime($file);
        $this->_info['size'] = $this->_toByteString(filesize($file));
        $this->_info['name'] = basename($file);;
        
        return (object)$this->_info;
    }
    
    private function _toByteString($size){
        $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        for ($i=0; $size >= 1024 && $i < 9; $i++) {
            $size /= 1024;
        }

        return round($size, 2) . $sizes[$i];
    }
}

?>
