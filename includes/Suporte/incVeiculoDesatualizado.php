<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template(substr($template_dir,3)."/veiculos_desatualizados.html");
	$T -> Set ("tpldir",substr($template_dir,6));

	
	// Definindo qual Contrato
	$T -> Set ("id", $id);
	
	// Apresentando o Contrato
	$T -> Show("Veiculos_IMPRIMIR_EMAIL");
        
        
	
?>