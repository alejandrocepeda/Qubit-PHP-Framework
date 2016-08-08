<?php 
/**
 * Clase para la conexión a bases de datos SQL y realizar operaciones comunes
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modifición: domingo 1 de marzo 2015
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Db
 */
class Qubit_Db{
    private static $_dbAdapter = array();
    const FETCH_NUM = 3;
    const FETCH_ASSOC = 2;
    const FETCH_OBJ = 5;
    
    const INT_TYPE    = 0;
    const BIGINT_TYPE = 1;
    const FLOAT_TYPE  = 2;

    public static function setAdapterDefault($adapter){
        self::$_dbAdapter['default'] = $adapter;
    }
    
    public static function addAdapter($adapter,$name){
        
        if (!isset(self::$_dbAdapter[$name])){
            self::$_dbAdapter[$name] = $adapter;
        }
    }
    
    public static function getAdapter($name = 'default'){
        
        if (isset(self::$_dbAdapter[$name])){
            return self::$_dbAdapter[$name];
        }
    }

    public static function factory($adapter,$config = array()){
        
        if ($config instanceof Qubit_Config) {
            $config = $config->getObject();

            $configDd['host']       = $config->db->host;
            $configDd['user']       = $config->db->user;
            $configDd['pwd']        = $config->db->pwd;
            $configDd['db_name']    = $config->db->db_name;
            $configDd['port']       = $config->db->port;   
        }
        else{
            $configDd = $config;
        }

        if (!is_array($configDd)) {
            throw new Qubit_Exception('El parametro de Configuración no es un array.' );
        }

        if (!is_string($adapter) || empty($adapter)) {
            throw new Qubit_Exception('El nombre del Adaptador no se ha especificado.' );
        }

        $adapterNamespace = 'Qubit_Db_Adapter';
        $adapterName = $adapterNamespace . '_';
        $adapterName .= str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($adapter))));
        
        if (!class_exists($adapterName)) {
            Qubit_Loader::LoadClass($adapterName);
        }
       
        $dbAdapter = new $adapterName($configDd);
       
        if (! $dbAdapter instanceof $adapterName) {
            throw new Qubit_Exception("La clase del Adaptador '$adapter' no existe.");
        }
        
        
        return $dbAdapter;       
    }
}
?>