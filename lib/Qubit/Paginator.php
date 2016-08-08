<?php
class Qubit_Paginator{
    
    private static $_Adapter = null;
   
       
    public static function factory($data){
        
        if (is_array($data)){
            self::$_Adapter = 'Array';
        }
        elseif ($data instanceof Qubit_Db_Select){
            self::$_Adapter = 'DbSelect';
        }
       
        if (!isset($data)) {
            throw new Qubit_Exception("Primer parametro $data no especificado.");
        }
        
        $adapterName = 'Qubit_Paginator_Adapter_' . self::$_Adapter; 
        
        if (!class_exists($adapterName)) {
             Qubit_Loader::LoadClass($adapterName);  
        }
       
        $dbAdapter = new $adapterName($data);
       
        if (! $dbAdapter instanceof $adapterName) {
            throw new Qubit_Exception("La clase del Adaptador '$adapterName' no existe.");
        }
        
        return $dbAdapter;
    }
}
?>