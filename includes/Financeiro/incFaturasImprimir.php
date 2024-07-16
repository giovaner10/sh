<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template(substr($template_dir,3)."/faturas.html");
	$T -> Set ("tpldir",substr($template_dir,6));

	
	// Definindo qual Contrato
	$T -> Set ("id", $id);
        $T -> Set ("numfat", $numero);
	
	// Apresentando o Contrato
	$T -> Show("Faturas_IMPRIMIR_BASE");
	
?>