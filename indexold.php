<?php

// Error Reporting
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 'on');

setlocale (LC_ALL, 'pt_BR');
date_default_timezone_set('America/Sao_Paulo');

// Modify include path to include path to library
ini_set(
	'include_path', 
	ini_get('include_path') . PATH_SEPARATOR .
	'../application' . PATH_SEPARATOR . 
	'../library'
	);

//Zend Framework Includes
require_once "Zend/Loader.php";


Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Auth');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Locale');
Zend_Loader::loadClass('Zend_Date');
Zend_Loader::loadClass('Zend_Layout');
//$layout = new Zend_Layout();
//$layout->setLayoutPath('../application/views/layouts');
//$layout->setLayout('foobaz');
//$layout->content = $content;
//echo $layout->render();
//$layout->setLayout('foobaz');
Zend_Layout::startMvc('../application/views/layouts');

//Zend_Layout::startMvc();


// load configuration
$config = new Zend_Config_Ini('../application/config.ini', 'database');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);

// setup database
$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

// Instanciando o Front Controller
$front = Zend_Controller_Front::getInstance();

// Criando parâmetro de configuração
Zend_Loader::loadClass("Zend_Config_Ini");
//$GLOBALS["config"] = new Zend_Config_Ini("configs/config.ini", "config");
//$GLOBALS["uploads"] = new Zend_Config_Ini("configs/config.ini", "uploads");

$front->setControllerDirectory('../application/controllers');
//$front->registerPlugin(new Acl_Plugin());
$front->throwExceptions(true);
// GO GO GO!!!
$front->dispatch();


