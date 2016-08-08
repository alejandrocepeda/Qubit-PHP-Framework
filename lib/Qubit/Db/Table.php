<?php
/**
 * Clase para interfaz de tabla SQL.
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modifición: domingo 1 de marzo 2015
 * Mas info: Qubit alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Db
 * @subpackage  Table 
 */


class Qubit_Db_Table{
    protected $_name = null;
    protected $_primary = null;
    protected $_schema = null;
    protected $_identity = 1;
    protected $_cols;
    protected $_metadata = array();
    protected $_adapter;
    
    const NAME = 'name';

    function __construct() {
       $this->_setup();
    }
    
    protected function _setup(){
        $this->_setupAdapter();
        $this->_setupTableName();
    }

    protected function _setupTableName(){
        if (!$this->_name) {
            $this->_name = get_class($this);
        }
    }

    public function info($key){

        $info = array(
            self::NAME  => $this->_name
        );

        if ($key === null) {
            return $info;
        }

        return $info[$key];
    }
    /*
     * estable el objeto del adaptador (Mysqli,Oracle,SQLServer,DB2 Etc) 
     */
    protected function _setupAdapter(){
        if (! $this->_adapter) {
            $this->_adapter = Qubit_Db::getAdapter();

            if (!$this->_adapter instanceof Qubit_Db_Adapter_Abstract) {
                throw new Qubit_Exception('No hay resultados para el adaptador ' . get_class($this));
            }
        }
    }

    protected function _getCols(){
        if (null === $this->_cols) {
            $this->_setupMetadata();
            $this->_cols = array_keys($this->_metadata);
        }
        return $this->_cols;
    }

    protected function _setupMetadata(){
        if (count($this->_metadata) > 0) {
            return true;
        }

        $this->_metadata = $this->_adapter->describeTable($this->_name, $this->_schema);    
    }

    public function find(){

        $this->_setupPrimaryKey();
        
        $args = func_get_args();
        $keyNames = array_values((array) $this->_primary);

        if (count($args) < count($keyNames)) {
            throw new Qubit_Exception("Muy pocas columnas para la clave primaria");
        }

        if (count($args) > count($keyNames)) {
            throw new Qubit_Exception("Muy pocas columnas para la clave primaria");
        }

        $whereList = array();
        $numberTerms = 0;
        foreach ($args as $keyPosition => $keyValues) {
            $keyValuesCount = count($keyValues);
            
            if (!is_array($keyValues)) {
                $keyValues = array($keyValues);
            }
            if ($numberTerms == 0) {
                $numberTerms = $keyValuesCount;
            } else if ($keyValuesCount != $numberTerms) {
                throw new Qubit_Exception("Falta el valore(s) para la clave primaria");
            }
            $keyValues = array_values($keyValues);
            for ($i = 0; $i < $keyValuesCount; ++$i) {
                if (!isset($whereList[$i])) {
                    $whereList[$i] = array();
                }
                $whereList[$i][$keyPosition] = $keyValues[$i];
            }
        }

        $whereClause = null;
        if (count($whereList)) {
            $whereOrTerms = array();
            $tableName = $this->_adapter->quoteTable($this->_name);

            foreach ($whereList as $keyValueSets) {
                $whereAndTerms = array();
                foreach ($keyValueSets as $keyPosition => $keyValue) {
                    $type = $this->_metadata[$keyNames[$keyPosition]]['DATA_TYPE'];
                    
                    $columnName = $this->_adapter->quoteTable($keyNames[$keyPosition]);

                    $whereAndTerms[] = $tableName . '.' . $columnName . ' = ' . $this->_adapter->quote($keyValue,$type);
                }
                $whereOrTerms[] = '(' . implode(' AND ', $whereAndTerms) . ')';
            }
            $whereClause = '(' . implode(' OR ', $whereOrTerms) . ')';
        }


        $select = $this->select();
        $select->from($tableName);

        $where = (array) $whereClause;

        foreach ($where as $key => $val) {
        
            if (is_int($key)) {
                $select->where($val);
            } else {
                $select->where($key, $val);
            }
        }
        
        return $this->fetchAll($select);
    }

   

    public function _setupPrimaryKey(){
        if (!$this->_primary) {
            $this->_setupMetadata();
            $this->_primary = array();
            
            foreach ($this->_metadata as $col) {
                if ($col['PRIMARY']) {
                    $this->_primary[$col['PRIMARY_POSITION'] ] = $col['COLUMN_NAME'];
                    if ($col['IDENTITY']) {
                       $this->_identity = $col['PRIMARY_POSITION'];
                    }
                }
            }

            if (empty($this->_primary)) {
                throw new Qubit_Exception('No existe una clave primaria para esta tabla');
            }
        }
        else if (!is_array($this->_primary)) {
            $this->_primary = array(1 => $this->_primary);
        }
        else if (isset($this->_primary[0])) {
            array_unshift($this->_primary, null);
            unset($this->_primary[0]);
        } 

        $cols = $this->_getCols();
        if (! array_intersect((array) $this->_primary, $cols) == (array) $this->_primary) {
            throw new Qubit_Exception("Columna de clave principal(s) ("
                . implode(',', (array) $this->_primary)
                . ") no son las columnas de esta tabla ("
                . implode(',', $cols)
                . ")");
        }
    }
    
    public function fetchAll($where = null, $order = null, $count = null, $offset = null){
        
        
        if (!($where instanceof Qubit_Db_Select)) {
            $select = $this->select();
            $select->from($this->_name,Qubit_Db_Select::SQL_WILDCARD);
            //$row = $select->query();
            
            if ($where !== null) {
                $select->where($where);
            }

            if ($order !== null) {
                $select->order($order);
            }

            if ($count !== null || $offset !== null) {
                $select->limit($count, $offset);
            }
        }
        else{
             $select = $where;
        }
        
        $rows = $this->fetch($select);

        return $rows;    
    }
    /*
    public function find($where){
       
       
        $select = $this->select()->where($where);
        $row = $select->query();
        
        if (count($row) == 1){
            
            return $row[0];
        }
        else{
            return $row;  
        }
    }
    */

    

    protected function _fetch(Qubit_Db_Select $select,$style = null){

        if ($style === null) {
            $style = Qubit_Db::FETCH_ASSOC;
        }

        $this->_adapter->query($select);
        $data = $this->_adapter->fetch($style);
        return $data;
    }

    public function fetchOne($where = null, $order = null, $limit = null, $offset = null){
        
        if (!($where instanceof Qubit_Db_Select)) {
            $select = $this->select();
            
            if ($where !== null) {
                $select->where($where);
            }

            if ($order !== null) {
                $select->order($order);
            }

            $select->limit(1, 0);
        }
        else{
             $select = $where->limit(1, 0);;
        }
        
        $row = $this->_fetch($select,Qubit_Db::FETCH_NUM); 

        if (count($row) == 0) {
            return null;
        }

        return $row[0][0];
    }
    
    public function fetchRow($where = null, $order = null, $limit = null, $offset = null){
        
        if (!($where instanceof Qubit_Db_Select)) {
            $select = $this->select();
            
            if ($where !== null) {
                $select->where($where);
            }

            if ($order !== null) {
                $select->order($order);
            }

            $select->limit(1, 0);
        }
        else{
             $select = $where->limit(1, 0);;
        }
        
        $row = $select->query();

        if (count($row) == 0) {
            return null;
        }

        return $row[0];
    }

    public function fetch($where = null, $order = null, $limit = null, $offset = null){
        
        if (!($where instanceof Qubit_Db_Select)) {
            $select = $this->select();
            
            if ($where !== null) {
                $select->where($where);
            }

            if ($order !== null) {
                $select->order($order);
            }

            if ($limit !== null || $offset !== null) {
                $select->limit($limit, $offset);
            }
        }
        else{
             $select = $where;
        }
        
        return $select->query();
    }
    
    /*
    public function __toString(){
        try {
            $data = $this->query();
        } catch (Qubit_Exception $e) {
            $data = '';
        }

        return $data;
    }
    */
    /*
     * retorna la instancia de la clase Qubit_Db_Select
     */
    public function select(){    
        $select = new Qubit_Db_Select($this->_adapter);
        $select
            ->setTableName($this->_name)
            ->select();
            

        return $select;
    }
    
    // EJECUTA LA SENTANCIA
    // recibe un array asociativo campo y valor
    public function insert($array){
        $table = $this->_adapter->quoteTable($this->_name);
        
        foreach($array as $key => $val){
            
            $val = $this->_adapter->escape_string($val);
            $noValid = false; 
            
            if (is_array($val)){
                if ($val[0] == $val[1]){
                   $noValid = true; 
                }
                else{
                    $val = $val[0];
                }
            }
            
            if (!$noValid){
                $datatype = gettype($val);

                if (is_numeric($val)){
                }
                elseif ($datatype == 'string'){
                        $val = "'".$val."'";
                }
                $fields.= (strlen($fields) == 0) ? "$key" : ",$key";
                $values.= (strlen($values) == 0) ? "$val" : ",$val";
            }
        }
        $query = 'INSERT INTO ' . $table . ' (' . $fields . ') VALUES (' . $values . ');';
        
        $this->_adapter->query($query);
        if ($this->_adapter->get_errno() == 0){
            return $this->_adapter->get_insert_id();
        }
        else{
            return $this->_adapter->get_errno();
        }
    }
    
    // EJECUTA LA SENTANCIA
    // recibe un array asociativo campo y valor
    public function delete($where = null){
        $table = $this->_adapter->quoteTable($this->_name);
        if (!$where == null){
                $where = "WHERE $where";
        }
        $query = 'DELETE FROM ' . $table . ' ' . $where . ';';
        
        $this->_adapter->query($query);
        if ($this->_adapter->get_errno() == 0){
            return true;
        }
        else{
            return $this->_adapter->get_errno();
        }
    }
    
    
    // EJECUTA LA SENTANCIA 
    // recibe un array asociativo campo y valor
    /*
     * @param string or array
     */
    public function update($array, $where = null){
        $table = $this->_adapter->quoteTable($this->_name);	
        $fields = '';

        foreach($array as $key => $val){	
            $noValid = false; 
            $val = $this->_adapter->escape_string($val);
            
            if (is_array($val)){
                if ($val[0] == $val[1]){
                   $noValid = true; 
                }
                else{
                    $val = $val[0];
                }
            }
            
            if (!$noValid){
                $datatype = gettype($val);			
                if (is_numeric($val)){                             
                }
                elseif ($datatype == 'string'){
                    $val = "'".$val."'";
                }

                $fields.= (strlen($fields) == 0) ?  "$key = $val" : ",$key = $val";
            }
        }
        if (!$where == null){
            $where = 'WHERE ' . $where;
        }
        $query = 'UPDATE ' . $table . ' SET ' . $fields . ' ' . $where . ';';
        
        $this->_adapter->query($query);

        if ($this->_adapter->get_errno() == 0){
            return true;
        }
        else{
            return $this->_adapter->get_errno();
        }
    }	
}
?>
