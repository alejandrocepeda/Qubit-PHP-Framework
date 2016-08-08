<?php
class Qubit_Locale{
    
    public static $_instance = null;
    public static $NumberDecimal = 0;
    public static $PointDecimal = ',';
    public static $ThousandSep  = '.';
    public static $MoneyFormat  = 'Bs.';
    public static $DateFormat  = 'd/m/Y';
    public static $TimeFormat  = 'h:i a';
    public static $DateTimeFormat  = 'd/m/Y h:i a';
    
    public static function setTimeFormat($format){
        self::$TimeFormat = $format;
    }
    public static function setDateTimeFormat($format){
        self::$DateTimeFormat = $format;
    }
    public static function setMoneyFormat($format){
        self::$MoneyFormat = $format;
    }
    public static function setDateFormat($format){
        self::$DateFormat = $format;
    }
    public static function setNumberDecimal($format){
        self::$NumberDecimal = $format;
    }
    public static function setPointDecimal($format){
        self::$PointDecimal = $format;
    }
    public static function setThousandSep($format){
        self::$ThousandSep = $format;
    }
    
    public static function getInstance(){
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
}

?>
