<?php
/**
 * Clase base Qubit_Config.
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modificaión: lunes 22 de febrero 2016
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Config
 */

class Qubit_Config{
    
    private static $_file = '';
    private static $_dataObj = '';
    
    public static function get(){
        return self::$_dataObj;
    }
    
    //opcional en caso de parse_ini_file() has been disabled for security reasons
    public static function parse_ini($filepath) {
        $ini = file( $filepath );
        if ( count( $ini ) == 0 ) { return array(); }
        $sections = array();
        $values = array();
        $globals = array();
        $i = 0;
        foreach( $ini as $line ){
            $line = trim( $line );
            // Comments
            if ( $line == '' || $line{0} == ';' ) { continue; }
            // Sections
            if ( $line{0} == '[' ) {
                $sections[] = substr( $line, 1, -1 );
                $i++;
                continue;
            }
            // Key-value pair
            list( $key, $value ) = explode( '=', $line, 2 );
            $key = trim( $key );
            $value = trim( $value );
            if ( $i == 0 ) {
                // Array values
                if ( substr( $line, -1, 2 ) == '[]' ) {
                    $globals[ $key ][] = $value;
                } else {
                    $globals[ $key ] = $value;
                }
            } else {
                // Array values
                if ( substr( $line, -1, 2 ) == '[]' ) {
                    $values[ $i - 1 ][ $key ][] = $value;
                } else {
                    $values[ $i - 1 ][ $key ] = $value;
                }
            }
        }
        for( $j=0; $j<$i; $j++ ) {
            $result[ $sections[ $j ] ] = $values[ $j ];
        }
        return $result + $globals;
    }

    public static function Read($file){
      
      if (!file_exists(PATH_APP . "/application/config/$file")) {
        return false;    
      }
      
      self::$_file = $file;  
        
      if(empty($file)){
         throw new Qubit_Exception("Falta el parametro del archivo INI");
      }

      $dataArray = self::parse_ini(PATH_APP . "/application/config/$file", TRUE);

      if ($dataArray){
        $dataObj = new stdClass();
        foreach($dataArray as $key => $value){
           if(is_array($value)){
              foreach($value as $key2 => $value2){
                 @$dataObj->$key->$key2 = $value2;
              }
           }
           else{
              $dataObj->$key = $value;
           }
        }

        self::$_dataObj = $dataObj;
        return $dataObj;
      }
      else{
          throw new Qubit_Exception("Error cargando el archivo de configuración.");
      }
      
   }
}
?>
