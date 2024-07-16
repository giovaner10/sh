<?php
/**
 * PAGINA PRINCIPAL AO SISTEMA
 */

ini_set('error_reporting','E_ALL & ~ ( E_NOTICE | E_WARNING | E_DEPRECATED)');

//Verifica se ja existe uma sessao criada
session_start();

// Classes necessarias
require_once 'classes/DB.class.php';
require_once 'classes/Log.class.php';
require_once 'classes/Template.class.php';

// Instanciando objeto de acesso ao BD e LOG
$DB = new DB ();
$LOG = new Log();

// Definindo a variavel $acao (ACAO)
extract($_REQUEST);
(!isset($acao))? $acao = "" : false;

// Verificando se a sessao esta aberta
// Caso a sessao esteja fechada,
// voltar a pagina de login.
if(!$_SESSION['l0g4d0'] || $acao == "sair"){
	
	// Criando log de logout
	$LOG->insertLog($DB, 'O usuário "'.$_SESSION["usuario_login"].'" saiu do sistema.');
	
	if ($acao == "sair") session_unset();
	
	// Direcionando para o LOGIN
	header("Location: login.php");
}


/*
 * Montando e apresentando a pagina segundo
 * o Template [main.html]
 */

$tpl = substr($template_dir,6);

// Instanciando um objeto do tipo P_Tpl
$T = new Template($tpl."/main.html");

// Definindo as variaveis do Template
$T -> Set("tpl_dir",$tpl);
$T -> Set("usuario_nome",$_SESSION['usuario_nome']);
$T -> Set("data",date('d/m/Y'));

// Apresentando o inicio da pagina
$T -> Show("Principal");
?>