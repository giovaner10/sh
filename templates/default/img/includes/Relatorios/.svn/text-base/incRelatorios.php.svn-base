<?php

	include_once '../incVerificaSessao.php';
	
	extract($_REQUEST);
	
	$T = new Template($template_dir."/relatorios.html");
	$T -> Set ("tpldir",substr($template_dir,6));
	
	// Definindo qual Relatorio
	$T -> Set ("relatorio","incRelatorios".$acao.".php");
	
	// Apresentando o Relatorio
	$T -> Show("Relatorios_BASE");
	
?>