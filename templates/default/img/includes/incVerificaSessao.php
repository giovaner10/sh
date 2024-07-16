<?php

ini_set('error_reporting','E_ALL & ~ ( E_NOTICE | E_WARNING | E_DEPRECATED)');

// Verificando se a sessao esta aberta
// Caso a sessao esteja fechada,
// voltar a pagina de login.

session_start();

if(!isset($_SESSION['l0g4d0'])){
	
	// Direcionando para o LOGIN
	header("Location: ../login.php");
}

// Classes necessarias
$class_dir = '../../classes';
$template_class = $class_dir.'/Template.class.php';
$class_dir = (file_exists($template_class)) ? $class_dir : substr($class_dir,3);

require_once $class_dir.'/Template.class.php';
require_once $class_dir.'/Html.class.php';
require_once $class_dir.'/DB.class.php';
require_once $class_dir.'/Log.class.php';

// Instaciando objetos da classe LOG
$LOG = new Log();

// Instanciando objetos da classe DB
$DB = new DB ();

// Instanciando objetos da classe HTML de acordo com o nivel do diretorio corrente
if(file_exists($template_class))
	$HTML = new Html();
else
	$HTML = new Html(substr($template_dir,3));

?>
