<?php
/**
 * PAGINA DE ACESSO AO SISTEMA
 */

//Verifica se ja existe uma sessao criada
session_start();

if(isset($_SESSION['l0g4d0'])){
	// Direcionando para o LOGIN
	header("Location: sistemaShowTecnologia.php");
}

// Classes necessarias
require_once 'classes/DB.class.php';
require_once 'classes/Template.class.php';
require_once 'classes/Login.class.php';

// Verificando o dados do usuario
if (isset($_POST['acao'])){
	
	// Instaciando um objeto do tipo DB
	$DB = new DB();

	// Instanciando um objeto do tipo Login
	$L = new Login();
	
	// Verificando dados do usuario
	$checa = $L -> checa($DB,$_POST['login'],$_POST['senha']);
	if ($checa){
		// Acessa a pagina inicial do sistema
		header("Location: sistemaShowTecnologia.php");
	} else {
		// Caso os dados estejam INCORRETOS
		$msg = "mostrar";
	}
}
if (!isset($msg)) $msg = "";

/*
 * Montando e apresentando a pagina segundo
 * o Template [login.html]
 */

$tpl = substr($template_dir,6);

// Instanciando um objeto do tipo Template
$T = new Template($tpl."/login.html");

// Definindo as variaveis do Template
$T -> Set("tpl_dir",$tpl);

// Apresentando a pagina
$T -> Show("inicio");
if ($msg == "mostrar")
	$T -> Show("mensagemErro");
$T -> Show("formulario");
$T -> Show("final");
?>