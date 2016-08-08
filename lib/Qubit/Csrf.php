<?php
/**
* @autor Alejandro Cepeda <alejandrocepeda25@gmail.com> 
*/

class Qubit_Csrf{
    protected $_hash;
    protected $_salt = 'salt';
    protected $name = '__no_csrf__';
    protected $timeout = 300;
    protected $message = "El formulario presentado no se originó en el sitio esperado";
	
   	public function __construct($options = array()){
   		
        foreach ($options as $key => $value) {
            switch (strtolower($key)) {
                case 'name':
                    $this->setName($value);
                    break;
                case 'salt':
                    $this->setSalt($value);
                    break;
                case 'timeout':
                    $this->setTimeout($value);
                    break;
                default:
                    break;
            }
        }
    }

    public function getMessage(){
         return $this->message;
    }

    public function getHash($regenerate = false){
        if ((null === $this->_hash) || $regenerate) {
            $this->generateHash();
        }
        return $this->_hash;
    }
	
    public function getTimeout(){
        return $this->timeout;
    }

    public function setTimeout($value){
        $this->timeout = ($value !== null) ? (int) $value : null;
        return $this;
    }

    public function setName($name){
        $this->name = (string) $name;
        return $this;
    }

    public function getName(){
        return $this->name;
    }
    
    public function setSalt($salt){
        $this->_salt = (string) $salt;
        return $this;
    }

    public function getSalt(){
        return $this->_salt;
    }

    protected function formatHash($token, $tokenId){
        return sprintf('%s-%s', $token, $tokenId);
    }

    protected function generateHash(){
        
        $token = md5($this->getSalt() . Qubit_Rand::getBytes(32) .  $this->getName());
        $TokenId = md5(Qubit_Rand::getBytes(32));
        $timeout = $this->getTimeout();

        if (null !== $timeout) {
            $this->setSession('tokenTime',(object)array('uptime' => time(),'timeout' => $timeout));
        }
        else{
            $this->unsetSession('tokenTime');
        }

        $this->_hash = $this->formatHash($token, $TokenId);

        if (!$this->sessionExists('tokenList')) {
            $this->setSession('tokenList',array());
        }

        $_SESSION['tokenList'][$TokenId] = $token;
        $this->setSession('hash',$this->_hash);
    }

    public function sessionExists($key){
        return isset($_SESSION[$key]);
    }

    public function unsetSession($key){
        unset($_SESSION[$key]);
    }

    public function setSession($key,$value){
        $_SESSION[$key] = $value;

        return $this;
    }

    public function getSession($key,$default = false){
        return $_SESSION[$key];
    }
	
    public function isValid($value, $context = null){

        $tokenId = $this->getTokenIdFromHash($value);
        $hash = $this->getValidationToken($tokenId);

        $tokenTime = $this->getSession('tokenTime');
        $uptime = $tokenTime->uptime; 
        $timeout = $tokenTime->timeout; 
     

        if ((time() - $uptime ) >= $timeout ) {
            return false;
        }

        if ($this->getTokenFromHash($value) !== $this->getTokenFromHash($hash)) {
            return false;
        }

        return true;
    }

    protected function getValidationToken($tokenId = null){

        if (!$tokenId && $this->sessionExists('hash')) {
            return $this->getSession('hash');
        }

        if ($tokenId && isset($_SESSION['tokenList'][$tokenId])) {
            return $this->formatHash($_SESSION['tokenList'][$tokenId], $tokenId);
        }

        return null;
    }

     protected function getTokenFromHash($hash){
        $data = explode('-', $hash);
        return $data[0] ?: null;
    }

    protected function getTokenIdFromHash($hash){
        $data = explode('-', $hash);

        if (!isset($data[1])) {
            return null;
        }

        return $data[1];
    }
}
?>
