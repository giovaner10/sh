<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template(substr($template_dir,3)."/propostas.html");
	$T -> Set ("tpldir",substr($template_dir,6));

	
	// Definindo qual Contrato
	$T -> Set ("id", $id);
	
	// Apresentando o Contrato
	$T -> Show("Contratos_IMPRIMIR_EMAIL");
        
        
	
?>