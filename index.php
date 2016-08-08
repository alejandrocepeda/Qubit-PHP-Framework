<?php
session_start();
ob_start();

$host = strtolower($_SERVER['SERVER_NAME']);

// quita los www y redirige sin www
if (substr($host,0,3) == 'www'){
  $host = str_replace('www.','',$host);	
  header("Location: http://" . $host . $_SERVER['REQUEST_URI']);
  exit();
}

define('PATH_APP',realpath(dirname(__FILE__)) . '/');
define('PATH_LIB', realpath(dirname(__FILE__)) . '/lib/');
define('PATH_ROOT',$_SERVER['DOCUMENT_ROOT']);

require(PATH_LIB . "/Qubit/Loader.php");

function __autoload($className) {   
    Qubit_Loader::LoadClass($className);
}

Qubit_Loader::setLibPath(PATH_LIB);

set_exception_handler(array('Qubit_Exception' , 'handle_exception'));

$request = Qubit_Request::getInstance();

$controller_name = $request->getParam('url','index');
$action_name = $request->getParam('action','index');

require(PATH_APP . 'application/bootstrap.php');

$bootstrap = new Bootstrap();
$bootstrap->init();
$bootstrap->setControllerName($controller_name)
->setActionName($action_name)
->setControllerPath(PATH_APP . 'application/controllers/')
->setViewPath(PATH_APP . 'application/views/')
->run();

?>