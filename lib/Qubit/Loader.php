<?php

/** 
 * @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
 * miercoles, 30 de julio de 2014
 * clase base para manejar Cargar archivos php 
 */
class Qubit_Loader {
     private static $_models = array();
     private static $_classess = array();
     private static $_libPath = '';
	 
     public static function setLibPath($path){
        self::$_libPath = realpath($path) . '/';
     }
    
     public static function camelcase($s, $lower=false){
		
        $s = ucwords(strtolower(strtr($s, '_', ' ')));
        $s = str_replace(' ', '', $s);

        if($lower) {
            $s = self::lcfirst($s);
        }
        return $s;
    }
    
    public static function LoadHelper($helper,$once = false){
        
         if (is_string($helper)){
            if (class_exists($helper, false) || interface_exists($helper, false)) {
                return;
            }

            $file .= self::PathFileDiscover($helper);
            
            if ($once) {
                include_once (PATH_APP . '/controllers/' . $file);
            } else {
                include (PATH_APP . '/controllers/' . $file);
            }
        }
        elseif (is_array($helper)){
            foreach ($helper as $filename){
                if (!class_exists($filename, false) || !interface_exists($filename, false)) {
                    $file = self::PathFileDiscover($filename);

                    if ($once) {
                        include_once (PATH_APP . '/controllers/' .$file);
                    } else {
                        include (PATH_APP . '/controllers/' .$file);
                    }
                }        
            }
        }
    }
    
    public static function LoadModel($model){
        
        if (!is_file(PATH_APP . "application/models/$model.php")){
            throw new Qubit_Exception('No existe el modelo ' . $model); 
        }
        
        if (!class_exists('Qubit_Db_Table', false)) {
           Qubit_Loader::LoadClass('Qubit_Db_Table');
        }
       
        if (!class_exists($model, false)) { 
            
            $file = PATH_APP . "application/models/$model.php";
            
            if (include_once($file)){
                self::$_models[$model] = new $model();
            }
            else{
                throw new Qubit_Exception('Error cargando el modelo ' . $model); 
            }
         }
        return self::$_models[$model];
    }

    

    private static function PathFileDiscover($class){

        $className = ltrim($class, '\\');
        $file      = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $file      = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $file .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        return $file;
    }
    
    public static function LoadClass($class,$dir = '', $once = false){
    
        if (is_string($class)){
            if (class_exists($class, false) || interface_exists($class, false)) {
                return;
            }
            
            $file = $dir . self::PathFileDiscover($class);
 
            if ($once) {    
                include_once (self::$_libPath . $file);
            } else {
                include (self::$_libPath . $file);
            }
        }
        elseif (is_array($class)){
            foreach ($class as $filename){
                if (!class_exists($filename, false) || !interface_exists($filename, false)) {
                    
                    $file = $dir . self::PathFileDiscover($filename);
                    
                    if ($once) {
                        include_once (self::$_libPath . $file);
                    } else {
                        include (self::$_libPath . $file);
                    }
                }        
            }
        }
    }
}
?>