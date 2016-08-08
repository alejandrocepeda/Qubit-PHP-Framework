<?php
include ('PHPMailer/phpmailer.php');

class Qubit_Mail extends PHPMailer {
    private $_body = null;
    private $_from = array();
    private $_to = array();
    private $_subject = null;
    
    public function setBody($body){
        $this->_body = $body;
        $this->Body($body);
        
        return $this;
    }
    
    public function ClearTo(){
        $this->ClearAddresses();
    }

    public function setFrom($from,$name = '', $auto = 1){
        $this->_from = array($from,$name);
        
        $this->From = $from;
        $this->FromName = $name;
        
        return $this;
    }
    
    public function addTo($mail,$name){
        $this->_to = array($mail,$name);
        $this->AddAddress($mail, $name);
        
        return $this;
    }
    
    public function setSubject($subject){
        $this->_subject = $subject;
        $this->Subject = $subject;
        
        return $this;
    }
    
    public function setMsgHTML($msg){
        $this->_subject = $msg;
        $this->MsgHTML($msg);
        
        return $this;
    }
    
    function __construct() {
        $this->CharSet = "UTF-8";
        $this->Port = 25;
        $this->AltBody = 'Para ver el mensaje, por favor, utilice un visor de HTML de correo electrónico compatibles';
    }


    public function SendMail(){
        return $this->Send();
    }
}
?>
