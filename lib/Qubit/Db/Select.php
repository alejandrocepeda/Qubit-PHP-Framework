<?php
/**
 * Clase para la generación y SELECT SQL
 * 
 * Creado por: Alejandro Cepeda   
 * Ultima modifición: jueves 2 de abril 2015    
 * Mas info: Qubit  alejandrocepeda25@gmail.com
 * 
 * @category    Qubit
 * @package     Qubit_Db
 * @subpackage  Select 
 */

class Qubit_Db_Select{
    
    /*public $_join = array();
    private $_cols = '';
    private $_cols_join = null;
    private $_cols_leftjoin = null;
    private $_union = null;
    private $_limit = null;
    private $_order = null;
    */
    private $_prefix = '';
    //public $_query_built = '';
    //public $_select = array();
    protected $_adapter;
    private $_name = null;
    public static $_instance = null;
    protected $_parts = array();

    
    const DISTINCT       = 'distinct';
    const COLUMNS        = 'columns';
    const FROM           = 'from';
    const UNION          = 'union';
    const WHERE          = 'where';
    const GROUP          = 'group';
    const HAVING         = 'having';
    const ORDER          = 'order';
    const LIMIT_COUNT    = 'limitcount';
    const LIMIT_OFFSET   = 'limitoffset';
    const FOR_UPDATE     = 'forupdate';
    const INNER_JOIN     = 'inner join';
    const LEFT_JOIN      = 'left join';
    const RIGHT_JOIN     = 'right join';
    const FULL_JOIN      = 'full join';
    const CROSS_JOIN     = 'cross join';
    const NATURAL_JOIN   = 'natural join';
    const FORCE_INDEX    = 'forceIndex';
    const USE_INDEX      = 'useIndex';
    
    const SQL_COLUMN_SEPARATOR = ',';
    const SQL_ALIAS_DELIMITOR  = '.';
    const SQL_WILDCARD         = '*';
    const SQL_SELECT           = ' SELECT '; 
    const SQL_FROM             = ' FROM '; 
    const SQL_WHERE            = ' WHERE '; 
    const SQL_AND              = ' AND ';
    const SQL_AS               = ' AS ';
    const SQL_UNION            = ' UNION ';
    const SQL_OR               = ' OR ';
    const SQL_ON               = ' ON ';
    const SQL_INNER            = ' INNER JOIN ';
    const SQL_LEFT_JOIN        = ' LEFT JOIN ';
    const SQL_ORDER            = ' ORDER BY ';
    const SQL_GROUP            = ' GROUP BY ';
    const SQL_LIMIT            = ' LIMIT ';
    const SQL_OFFSET           = ' OFFSET ';
    const SQL_USE_INDEX        = ' USE INDEX ';
    const SQL_FORCE_INDEX      = 'FORCE INDEX';

    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    public function setPrefix($value){
        $this->_prefix = $value;
        return $this;
    }
    
    public function setTableName($name){
        $this->_name = $name;
        return $this;
    }
    
    public function __construct($adapter = 'mysql') {
        $this->_adapter = $adapter;
        
        return $this;
    }

    public function QuoteSymbol($value){
        return "`" . $value . "`";
    }
    
   

    public function resetSelect($part = null){
        if ($part == null) {
            $this->_parts[self::WHERE] = array();
            $this->_parts[self::FROM] = '';
            $this->_parts[self::LEFT_JOIN] = array();
            $this->_parts[self::INNER_JOIN] = array();
            $this->_parts[self::COLUMNS] = '';
            $this->_parts[self::ORDER] = '';
            $this->_parts[self::LIMIT_COUNT] = '';
            $this->_parts[self::UNION] = array();
            //$this->query_built = '';
        }
        else{
            $this->_parts[$part] = array();
        }
    }

    public function columns($cols = '*', $_alias = null){
        
        $this->_tableCols($cols, $_alias);

        return $this;
    }

    public function forceIndex($index){

        if(empty($this->_parts[self::USE_INDEX])) {
            if(!is_array($index)) {
                $index = array($index);
            }

            $this->_parts[self::FORCE_INDEX] = $index;   
            return $this;
        }
        else{
            throw new Qubit_Exception("No se puede utilizar 'FORCE INDEX' en la misma consulta con 'USE INDEX'");
        }
    }

    public function useIndex($index){

        if(empty($this->_parts[self::FORCE_INDEX])) {
            if(!is_array($index)) {
                $index = array($index);
            }

            $this->_parts[self::USE_INDEX] = $index;    
            return $this;
        }
        else{
            throw new Qubit_Exception("No se puede utilizar 'USE INDEX' en la misma consulta con 'FORCE INDEX'");
        }
    }

    public function select(){    
        $this->resetSelect();
        return $this;
    }
    
    /*
    protected function _fetch(Qubit_Db_Select $select){
        $this->_adapter->query($select);
        $data = $this->_adapter->fetch(Zend_Db::FETCH_ASSOC);
        return $data;
    }
    */

    public function query(){
        $this->_adapter->query($this);
        return $this->_adapter->get_array();
    }
    
    private function _renderFrom(){

        if(!empty($this->_parts[self::FORCE_INDEX])) {
            $tmp = ' ' . self::SQL_FORCE_INDEX . '(' . implode(',', $this->_parts[self::FORCE_INDEX]) . ')';  
        }

        if(!empty($this->_parts[self::USE_INDEX])) {
            $tmp = ' ' . self::SQL_USE_INDEX . '(' . implode(',', $this->_parts[self::USE_INDEX]) . ')';
        }

        $tpm.= self::SQL_FROM . $this->_parts[self::FROM] . $tmp;

        return $tpm;
    }

    private function _renderWhere(){
        if(!empty($this->_parts[self::WHERE])) {
            $sql .= ' ' . self::SQL_WHERE . ' ' .  implode(' ', $this->_parts[self::WHERE]);
        }

        return $sql;
    }

    private function _renderLeftJoin(){
        if(!empty($this->_parts[self::LEFT_JOIN])) {
            $sql .= ' ' . implode('', $this->_parts[self::LEFT_JOIN]);
        }

        return $sql;
    }

    private function _renderInnerJoin(){
        if(!empty($this->_parts[self::INNER_JOIN])) {
            $sql .= ' ' . implode('', $this->_parts[self::INNER_JOIN]);
        }

        return $sql;
    }

    private function _renderCols(){
        return $this->_parts[self::COLUMNS];
    }

    
    private function _renderGroup(){
        
        if(!empty($this->_parts[self::GROUP])) {
            $sql = self::SQL_GROUP . $this->_parts[self::GROUP]; 
        }

        return $sql;
    }

    private function _renderOrder(){
        
        if(!empty($this->_parts[self::ORDER])) {
            $sql = self::SQL_ORDER . $this->_parts[self::ORDER]; 
        }

        return $sql;
    }

    private function _renderLimitCount(){
        
        if(!empty($this->_parts[self::LIMIT_COUNT])) {
            $sql = $this->_parts[self::LIMIT_COUNT];
        }

        return $sql;
    }

    private function _renderUnion($query){
        
        if (!empty($this->_parts[self::UNION])){

            $sql = '(' . $query . ') ';
            $sql.=  implode(' (' . self::SQL_UNION . ') ', $this->_parts[self::UNION]);
        }

        return $sql;
    }
    
    public function getPart($part){
         return $this->_parts[$part];
    }

    public function assemble(){
        
        $query = self::SQL_SELECT;
        $query.= $this->_renderCols();
        $query.= $this->_renderFrom();
        $query.= $this->_renderInnerJoin();
        $query.= $this->_renderLeftJoin();
        $query.= $this->_renderWhere();
        $query.= $this->_renderGroup();
        $query.= $this->_renderOrder();
        $query.= $this->_renderLimitCount();
        $query.= $this->_renderUnion($query);
        
        //$this->_query_built = $query;
        
        return $query; 
    }
    
    /*
     * @param (string,array) $name: nombre de la tabla del query
     * si este parametro es array Ej: array('p' => 'clientes') el key es el alias y el value es el nombre de la tabla para generar Ej: p.clientes
     * @cols optional (string,array): array con los nombres de los campos para el select Ej: array('nombre','apellido') para generar nombre,apellido
     * de no recibir este parametro se agrega el * para que tome todos los campos  
     */
    public function from($name = null,$cols = self::SQL_WILDCARD){
        
        if (!is_array($cols)) {
            $cols = array($cols);
        }

        if (is_array($name) and count($name) == 1){
            current($name);
            $_alias = key($name);
            $this->_parts[self::FROM] = $this->QuoteSymbol($this->_prefix . $name[key($name)]) . self::SQL_AS . $_alias;
        }
        elseif (is_string($name)){
            $_alias = $name;
            $this->_parts[self::FROM] =   $this->QuoteSymbol($this->_prefix . $this->_name) . self::SQL_AS . $name;
        }

        //if (is_array($cols)){
            /*
            foreach ($cols as $val) {
                if ($val instanceof Qubit_Db_Expr) {
                    $_parts[] = $val->__toString();
                }
                else{
                    if($_alias != null){
                        $_parts[] = $_alias . self::SQL_ALIAS_DELIMITOR . $val;
                    }
                    else{
                        $_parts[] = $val;    
                    }
                }
            }
            */
            //$this->_parts[self::COLUMNS] = implode(self::SQL_COLUMN_SEPARATOR, $_parts);
            
            $this->_tableCols($cols,$_alias);
        //}
        
        /*elseif ($cols instanceof Qubit_Db_Expr){
            $this->_parts[self::COLUMNS] = $cols->__toString();
        }    
        elseif (is_string($cols)){
            $this->_parts[self::COLUMNS] = ($_alias != null) ? $_alias . self::SQL_ALIAS_DELIMITOR . $cols : $cols;
        }
        */
       
        return $this;
    }
    
    public function quoteInto($value,$text){

        if (strpos($text, '?') !== false) {
            $text = str_replace('?',$this->quote($value),$text);
        }
        else{ 
            $text.= $this->quote($value);
        }
         
        return $text;   
    }

     public function quote($value){

        if (is_int($value)) {
            return $value;
        } elseif (is_float($value)) {
            return sprintf('%F', $value);
        }

        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";      
    }

    public function where($condition,$value = null){

        if ($value != null) {
            $condition = $this->quoteInto($value,$condition);
        }

        $condition = " ($condition) ";

        if (count($this->_parts[self::WHERE]) == 0){
            $this->_parts[self::WHERE][] = $condition;
        }
        else{
            $this->_parts[self::WHERE][] = self::SQL_AND . $condition;
        }

        return $this;
    }
    
    public function orwhere($condition,$value = null){

        if ($value != null) {
            $condition = $this->quoteInto($value,$condition);
        }

        $condition = " ($condition) ";

        if (count($this->_parts[self::WHERE]) == 0){
            $this->_parts[self::WHERE][] = $condition;
        }
        else{
            $this->_parts[self::WHERE][] = self::SQL_OR . $condition;
        }

        return $this;
    }
    
    public function order($order){
        if (is_array($order)){
            $this->_parts[self::ORDER] = implode(',', $order);
        }
        elseif(is_string($order)){
           $this->_parts[self::ORDER] = $order;
        }
        
        return $this;
    }

    public function group($group){
        if (is_array($group)){
            $this->_parts[self::GROUP] = implode(',', $group);
        }
        elseif(is_string($group)){
           $this->_parts[self::GROUP] = $group;
        }
        
        return $this;
    }
    
    public function __toString(){
        try {
            $sql = $this->assemble();
            //$sql = $this->_query_built;
        } catch (Qubit_Exception $e) {
            $sql = '';
        }
       
        return $sql;
    }

    public function union($select){
        $this->_parts[self::UNION][] = $select->__toString();
        return $this;
    }
    
    public function limit($limit = null,$offset = null){
        
        if ($limit == null and $offset == null){
            return $this;
        }
        
        $this->_parts[self::LIMIT_COUNT] = self::SQL_LIMIT . $limit . self::SQL_OFFSET . $offset;
        return $this;
    }
    
    public function leftjoin($name,$join_on,$join_cols = array()){
        
        if (is_array($name) and count($name) == 1){
            current($name);
            $_alias = key($name);
            $this->_parts[self::LEFT_JOIN][] =  self::SQL_LEFT_JOIN . $this->QuoteSymbol($this->_prefix . $name[key($name)]) . self::SQL_AS . $_alias . self::SQL_ON . $join_on;
        }
        elseif (is_string($name)){
            $this->_parts[self::LEFT_JOIN][] =  self::SQL_LEFT_JOIN . $this->QuoteSymbol($this->_prefix . $name) . self::SQL_ON . $join_on;
        }
        
        $this->_tableCols($join_cols,$_alias);
       
        return $this;
    }

    private function implodeArrayColumn($cols,$_alias){
        
        $cols_inner = array();
        
        foreach($cols as $key){
            if ($key instanceof Qubit_Db_Expr){   
                $cols_inner[] =  $key->__toString();
            }
            else{
                $cols_inner[] =  $_alias . self::SQL_ALIAS_DELIMITOR . $key;
            }       
        }
        
        return implode(self::SQL_COLUMN_SEPARATOR, $cols_inner);
    }

    private function _tableCols($cols,$alias = null){

        if (!is_array($cols)) {
            $cols = array($cols);
        }

        if (empty($this->_parts[self::COLUMNS])){
            $this->_parts[self::COLUMNS] = $this->implodeArrayColumn($cols,$alias);
        }
        else{
            $this->_parts[self::COLUMNS].= self::SQL_COLUMN_SEPARATOR . $this->implodeArrayColumn($cols,$alias);
        }
    }

    /*
     * @param string $name: 
     */
    public function join($name,$join_on,$join_cols = array()){

        if (is_array($name) and count($name) == 1){
            current($name);
            $_alias = key($name);
            $this->_parts[self::INNER_JOIN][] =  self::SQL_INNER . $this->QuoteSymbol($this->_prefix . $name[key($name)]) . self::SQL_AS . $_alias . self::SQL_ON . $join_on;
        }
        elseif (is_string($name)){
            $_alias = null;
            $this->_parts[self::INNER_JOIN][] =  self::SQL_INNER . $this->QuoteSymbol($this->_prefix . $name) . self::SQL_ON . $join_on;
        }
        
        $this->_tableCols($join_cols,$_alias);
       
        return $this;
    }
}
/*
 * 
 * Ejemplo de usa de esta clase
$db = new db();

$selectA = $db->select()
        ->from(array('u' => 'usuarios'),array('nombre','apellido'))  
        ->join(array('t' => 'tickets'),'u.id_usuario = t.id_usuario',array('deporte','deporte'))
                ->leftjoin(array('c' => 'cierre'),'u.id_usuario = c.id_usuario',array('ganancia','perdida'))
        ->where('t.monto > 100')
        ->where("c.fecha = '23/02/2013'")
        ->orwhere('u.id = 1')
                ->orwhere('t.id_taquilla > 1')
        ->order(array('u.id_usuario DESC','u.id_taquilla ASC'))
                ->limit(20, 10);

$selectB = $db->select()
        ->from(array('u' => 'clientes'),array('nombre','apellido'))  
        ->join(array('t' => 'tickets'),'u.id_usuario = t.id_usuario',array('deporte','deporte'))
                ->leftjoin(array('c' => 'cierre'),'u.id_usuario = c.id_usuario',array('ganancia','perdida'))
        ->where('t.monto > 100')
        ->where("c.fecha = '23/02/2013'")
        ->orwhere('u.id = 1')
                ->orwhere('t.id_taquilla > 1')
        ->order(array('u.id_usuario DESC','u.id_taquilla ASC'))
                ->limit(20, 10)
                ->union($selectA);

echo $selectB; 
 * 
 */
?>

