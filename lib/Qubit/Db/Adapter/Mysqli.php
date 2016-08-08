<?php
/**                                                   
 * Clase para la conexión a bases de datos SQL y realizar operaciones comunes.
 * 
 * Creado por: Alejandro Cepeda                     
 * Ultima modifición: domingo 1 de marzo 2015
 * Mas info: alejandrocepeda25@gmail.com    
 * 
 * @category    Qubit
 * @package     Qubit_Db
 * @subpackage  Adapter 
 * @copyright   Qubit  alejandrocepeda25@gmail.com                                                      
 */

class Qubit_Db_Adapter_Mysqli extends Qubit_Db_Adapter_Abstract{
    private $resource;
    private $sql;
    protected $_fetchMode = Qubit_Db::FETCH_ASSOC;    

    /*
     * @param array $confif: arreglo asociativo con los parametros de conexion 
     * array(host => 'localhost',user => 'root', pwd => '010203',db_name => 'general',port => 3306)
     */
    function __construct($config = array()){
           
            
            $this->_connection = mysqli_init();
            $connected = @mysqli_real_connect(
                    $this->_connection,
                    $config['host'],
                    $config['user'],
                    $config['pwd'],
                    $config['db_name'],
                    $config['port']
                );

            if ($connected == false){
                throw new Qubit_Exception(mysqli_connect_error()); 
            }
            
            mysqli_set_charset($this->_connection, 'utf8');

            $this->resource = null;
    }

    protected function _connect(){
        if ($this->_connection) {
            return;
        }    
    }

    public function _foldCase($key){
        return strtolower((string) $key);
    }

    public function describeTable($tableName, $schemaName = null){
       
        if ($schemaName) {
            $sql = 'DESCRIBE ' . $schemaName . '.' . $this->quoteTable($tableName);
        } else {
            $sql = 'DESCRIBE ' . $this->quoteTable($tableName);
        }

        if ($queryResult = $this->getConection()->query($sql)) {
            while ($row = mysqli_fetch_assoc($queryResult)) {
                $result[] = $row;
            }
            $queryResult->close();
        } else {
            throw new Qubit_Exception($this->getConnection()->get_error());
        }  
            
        $desc = array();

        $row_defaults = array(
            'Length'          => null,
            'Scale'           => null,
            'Precision'       => null,
            'Unsigned'        => null,
            'Primary'         => false,
            'PrimaryPosition' => null,
            'Identity'        => false
        );
        $i = 1;
        $p = 1;
        foreach ($result as $key => $row) {
            $row = array_merge($row_defaults, $row);
            if (preg_match('/unsigned/', $row['Type'])) {
                $row['Unsigned'] = true;
            }
            if (preg_match('/^((?:var)?char)\((\d+)\)/', $row['Type'], $matches)) {
                $row['Type'] = $matches[1];
                $row['Length'] = $matches[2];
            } else if (preg_match('/^decimal\((\d+),(\d+)\)/', $row['Type'], $matches)) {
                $row['Type'] = 'decimal';
                $row['Precision'] = $matches[1];
                $row['Scale'] = $matches[2];
            } else if (preg_match('/^float\((\d+),(\d+)\)/', $row['Type'], $matches)) {
                $row['Type'] = 'float';
                $row['Precision'] = $matches[1];
                $row['Scale'] = $matches[2];
            } else if (preg_match('/^((?:big|medium|small|tiny)?int)\((\d+)\)/', $row['Type'], $matches)) {
                $row['Type'] = $matches[1];
                /**
                 * The optional argument of a MySQL int type is not precision
                 * or length; it is only a hint for display width.
                 */
            }
            if (strtoupper($row['Key']) == 'PRI') {
                $row['Primary'] = true;
                $row['PrimaryPosition'] = $p;
                if ($row['Extra'] == 'auto_increment') {
                    $row['Identity'] = true;
                } else {
                    $row['Identity'] = false;
                }
                ++$p;
            }

            $desc[$this->_foldCase($row['Field'])] = array(
                'SCHEMA_NAME'      => null, // @todo
                'TABLE_NAME'       => $this->_foldCase($tableName),
                'COLUMN_NAME'      => $this->_foldCase($row['Field']),
                'COLUMN_POSITION'  => $i,
                'DATA_TYPE'        => $row['Type'],
                'DEFAULT'          => $row['Default'],
                'NULLABLE'         => (bool) ($row['Null'] == 'YES'),
                'LENGTH'           => $row['Length'],
                'SCALE'            => $row['Scale'],
                'PRECISION'        => $row['Precision'],
                'UNSIGNED'         => $row['Unsigned'],
                'PRIMARY'          => $row['Primary'],
                'PRIMARY_POSITION' => $row['PrimaryPosition'],
                'IDENTITY'         => $row['Identity']
            );
            ++$i;
        }
        
        
        return $desc;
    }

    public function security($val){
            return sha1($val);
    }
        
    public function call($sql,$params = array()){   
        if (!is_array($params)){
            throw new Qubit_Exception('Los parametros del Procedure debe ser un array');
        }
        elseif (is_array($params)){
            foreach($params as $key){

                $datatype = gettype($key);
                if (is_numeric($key)){
                }
                elseif ($datatype == 'string'){
                        $key = "'".$key."'";
                }
                $values.= (empty($values)) ? $key : ",$key";
            }
        }
        
        if (@mysqli_multi_query($this->_connection, 'call ' . $sql . "($values)")) {
            $array = array();   
             do {
                 if ($result = mysqli_store_result($this->_connection)) {

                     while ($row = mysqli_fetch_object($result)) {
                         $array[] = $row;
                     }
                     mysqli_free_result($result);
                 }

             } while (mysqli_next_result($this->_connection));
             return $array;
        }
        else{
            throw new Qubit_Exception($this->get_error());
        }
    }

    public function select($table,$fields = '*',$where = '',$order = '',$limit = ''){
        if (!empty($where)){
            $where = ' WHERE ' . $where;
        }
        if (!empty($order)){
            $order = ' ORDER BY ' . $order;
        }       

        $sql = 'SELECT ' . $fields . ' FROM ' . $this->quoteTable($table). ' ' . $where . ' ' . $order . ' ' . $limit;           
        $this->query($sql);
         
        if ($this->get_errno() == 0){
            return $this->get_array();
        }
        else{
            throw new Qubit_Exception($this->get_error(),$this->get_errno());
        }
    }
    
    public function setFilter($campos,$filtro,$criterio = ''){
        if (!empty($filtro)){
            $a_campos = explode(',',$campos);   
            for($i=0;$i<count($a_campos);$i++){
                if (strlen($cadena) == 0){
                        $cadena.=" UPPER(".$a_campos[$i].") like (UPPER('%$filtro%')) ";
                }
                else{
                        $cadena.=" OR UPPER(".$a_campos[$i].") like (UPPER('%$filtro%')) ";
                }   
            }
        }


        // si los 2 pam son vacios no devuelve nada
        if (empty($criterio) and empty($filtro)){
            $salida = '';
        }
        elseif (!empty($filtro) and !empty($criterio)){
            //$salida = ' ('.$cadena.') ';
            $salida = ' ('.$criterio.') AND ('.$cadena.') ';
        }
        elseif (!empty($filtro) and empty($criterio)){
            $salida = ' ('.$cadena.' )';
        }
        elseif (empty($filtro) and !empty($criterio)){
            $salida = ' ('.$criterio.' )';
        }

        // si alguno de los 2 parametros $criterio o $filtro no sin vacios se agrega la cadena WHERE si $adwhere es true
        if (!empty($criterio) or !empty($filtro)) {
            $salida = ' WHERE '.$salida;
        }

        return $salida;
    }
    
    public function set_db($db_name) {
        return mysqli_select_db($this->_connection,$db_name);
    }

    public function execute_query($sql){
            if(!(@mysqli_query($this->_connection,$sql))){
                    throw new Qubit_Exception($this->get_error(),$this->get_errno()); 
            }

            return true;
    }   
    
    public function fetchAll($table,$fields = ' * '){
            $this->sql = 'SELECT ' . $fields . ' FROM ' . $this->quoteTable($table);
            if (!$this->execute()){
                    throw new Qubit_Exception($this->get_error(),$this->get_errno()); 
            }
            else{
                    $array = array();
                    while ($row = @mysqli_fetch_object($this->resource)){
                            $array[] = $row;
                    }
                    return $array;
            }
    }   

    public function getOne($sql){   
            // TIENE QUE SER UNA CONSULTA SELECT PARA PONER AL FINAL LIMIT 1 OFFSET 0
            // DE LO CONTRARIO ES UN PROCEDURE
            if (strpos(strtoupper($sql),'SELECT') !== false){
                    if (strpos(strtoupper($sql),'FROM') !== false){
                            if (strpos(strtoupper($sql),"LIMIT") == false){
                                    $sql.=' LIMIT 1 ';
                            }
                            if (strpos(strtoupper($sql),"OFFSET") == false){
                                    $sql.=' OFFSET 0';
                            } 
                    }
            }

            if(!($result = @mysqli_query($this->_connection,$sql))){
                    throw new Qubit_Exception($this->get_error(),$this->get_errno()); 
            }
            else{
                    $this->sql = $sql;
                    $fila = mysqli_fetch_row($result);
                    $salida = $fila[0]; 

                    if (strpos(strtoupper($sql),'SELECT') !== false){
                            // limpia los resultados cuando es una consulta
                            mysqli_free_result($result);
                    }
                    elseif (strpos(strtoupper($sql),'CALL') !== false){
                            // limpia los resultados cuando es un procedimiento almacenado
                            while(mysqli_next_result($this->_connection)){
                                    if($result = mysqli_store_result($this->_connection)){
                                            mysqli_free_result($result);
                                    }
                            }
                    }           
                    return $salida;
            }
    }

    public function alter(){
            if(!($this->resource = @mysqli_query($this->_connection,$this->sql))){
                    throw new Qubit_Exception($this->get_error(),$this->get_errno()); 
            }
            return true;
    }

    public function querylimit($sql,$star,$end){
            if(empty($sql)){
                    return false;
            }
            $this->sql = $sql.' LIMIT '.$star.' OFFSET '.$end;
            return true;
    }   
    
    public function execute_multi_query($sql){  
            if(!(@mysqli_multi_query($this->_connection,$sql))){
                    throw new Qubit_Exception($this->get_error(),$this->get_errno()); 
            }
            else{
                    $this->sql = $sql;
                    return true;
            }
    }
        
    //PARA PROTEGER SQL INJECT
    public function escape_string($string){
            if(get_magic_quotes_gpc())   {
                $string = stripslashes($string);
            }

            if (phpversion() >= '4.3.0') {
                $string = mysqli_real_escape_string($this->_connection,$string);
            }
            else {
                $string = mysqli_escape_string($this->_connection,$string);
            }
            return $string;
    }
    
    
    public function query($sql){    
           
            if(empty($sql)){
                return null;
            }
            $this->sql = $sql;
            if (!($cur = $this->execute())){
                    return null;
            }

            return true;
    }

    public function execute(){
           
            if(!($this->resource = @mysqli_query($this->_connection,$this->sql))){
                    throw new Qubit_Exception($this->get_error(),$this->get_errno()); 
                    //return null;
            }
            else{
                    return $this->resource;
            }
    }

     public function fetch($style = null){

        if (!$this->resource){
            return null;
        }

        if ($style === null) {
            $style = $this->_fetchMode;
        }
        
        $row = false;
        switch ($style) {
            case Qubit_Db::FETCH_NUM:
                $values = array();
                while ($row = mysqli_fetch_array($this->resource)){
                    $values[] = $row;
                }
                break;
            case Qubit_Db::FETCH_ASSOC:
                $values = array();
                while ($row = mysqli_fetch_assoc($this->resource)){
                    $values[] = $row;
                }

                break;
           
            case Qubit_Db::FETCH_OBJ:
                
                $values = array();
                while ($row = mysqli_fetch_object($this->resource)){
                    $values[] = $row;
                }
                break;
           
            default:  
                throw new Qubit_Exception("Invalid fetch mode '$style' specified");
                break;
        }
        return $values;
    }


    public function get_array(){
            if (!$this->resource){
                return null;
            }

            $array = array();

            while ($row = @mysqli_fetch_object($this->resource)){
                    $array[] = $row;
            }
            return $array;
    }
    
    public function getRow($sql){

            if (!($cur = @mysqli_query($this->_connection,$sql))) {
                throw new Qubit_Exception($this->get_error(),$this->get_errno());
            }
            else{
                    $this->sql = $sql;
                    if (mysqli_num_rows($cur) > 0){
                            $row = @mysqli_fetch_object($cur);

                            if (strpos(strtoupper($sql),'SELECT') !== false){
                                    // limpia los resultados cuando es una consulta
                                    mysqli_free_result($cur);
                            }
                            elseif (strpos(strtoupper($sql),'CALL') !== false){
                                    // limpia los resultados cuando es un procedimiento almacenado
                                    while(mysqli_next_result($this->_connection)){
                                            if($cur = mysqli_store_result($this->_connection)){
                                                    mysql_free_result($cur);
                                            }
                                    }
                            }
                            return $row;
                    }
                    else{
                            return null;
                    }
            }

    }
    
    public function getRows($sql){
            if (!($cur = @mysqli_query($this->_connection,$sql))) {
                   throw new Qubit_Exception($this->get_error(),$this->get_errno());
            }
            else{
                    if (mysqli_num_rows($cur) > 0){
                            $this->sql = $sql;
                            $row = @mysqli_fetch_object($cur);

                            if (strpos(strtoupper($sql),'SELECT') !== false){
                                    // limpia los resultados cuando es una consulta
                                    mysqli_free_result($cur);
                            }
                            elseif (strpos(strtoupper($sql),'CALL') !== false){
                                    // limpia los resultados cuando es un procedimiento almacenado
                                    while(mysqli_next_result($this->_connection)){
                                            if($cur = mysqli_store_result($this->_connection)){
                                                    mysql_free_result($cur);
                                            }
                                    }
                            }
                            return $row;
                    }
                    else{
                            return null;
                    }
            }
    }
    
    public function get_num_rows(){
            if (!($num = mysqli_num_rows($this->resource))){
                    return null; 
            }
            return $num;
    }

    public function get_insert_id(){ 
            return mysqli_insert_id($this->_connection);
    }
    

    public function get_affected_rows(){
            return mysqli_affected_rows($this->db);
    }
    
    public function get_errno(){
            $error = mysqli_errno($this->_connection);
            return $error;
    }
    
    public function get_error(){
            $error = addslashes(mysqli_error($this->_connection));
            return $error;
    }

    public function free_result(){
            @mysqli_free_result($this->resource);
            return true;
    }
        
    public function closeConnection(){
            @mysqli_close($this->_connection);
    }
}
?>