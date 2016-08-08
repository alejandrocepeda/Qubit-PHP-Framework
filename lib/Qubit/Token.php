<?php
class Qubit_Token {
    private static $_randomkey = 'suP3rR4nDKeY';
    private static $_fixedkey = '123lsdlas345';
    private static $_token_expire_time = 300;

    public static function setRandomkey($value){
        self::$_randomkey = $value;
        return self;
    }
    public static function setTokenExpireTime($time){
        self::$_token_expire_time = $time;
        return self;
    }
    public static function setFixedkey($value){
        self::$_fixedkey = $value;
        return self;
    }

    public static function csrfAuthenticate(){
       // $id_form es el id obtenido del formulario
       // $form_token es el token del formulario

       $token = Qubit_Request::getInstance()->getParam('auth_token',false);
       $salt = Qubit_Request::getInstance()->getParam('auth_salt',false);
       
       $token_age = time() - $_SESSION['token_time']; 
       
      
       
       if ($token_age > self::$_token_expire_time){
           throw new Qubit_Exception('La página a expirado..');
           //return false;
       }
       
       if ($token != false and $salt != false){

           $check_token = sha1(self::$_randomkey . self::$_fixedkey . $salt);
           
           if ($token == $check_token ) {
               return true;
           } else {
               //return false;
               throw new Qubit_Exception('Error en la aplicación');
           }
       }
       else{
           //return false;
           throw new Qubit_Exception('Error en la aplicación');
       }
    }
    
    public static function RandomString($length = 8,$uc = true,$n = true,$sc = false){
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($n==1) $source .= '1234567890';
        if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
        if($length>0){
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++){
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }

        }
        return $rstr;
    }

    public static function createToken(){
        // codigo en el encabezado del form
        $salt = md5(uniqid(mt_rand(), true)); 
        $_SESSION['token_time'] = time();
        
        
        // token debera ir en un campo oculto del formulario.
        $token = sha1(self::$_randomkey . self::$_fixedkey . $salt);
       
        
        return (object) array(auth_token =>$token,auth_salt => $salt);
    }
    
}
?>
