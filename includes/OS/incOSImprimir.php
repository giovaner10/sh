<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template(substr($template_dir,3)."/os.html");
	$T -> Set ("tpldir",substr($template_dir,6));

	
	// Definindo qual Contrato
	$T -> Set ("id", $id);
	
	// Apresentando o Contrato
	$T -> Show("OS_IMPRIMIR_BASE");
	
?>