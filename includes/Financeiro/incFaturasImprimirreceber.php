<?php

	include_once '../incVerificaSessao.php';
	
	$T = new Template(substr($template_dir,3)."/faturasreceber.html");
	$T -> Set ("tpldir",substr($template_dir,6));

	
	// Definindo qual Contrato
	$T -> Set ("id", $id);
        $T -> Set ("ini", $ini);
        $T -> Set ("fim", $fim);
        $T -> Set ("ped", $ped);
	
	// Apresentando o Contrato
	$T -> Show("Faturas_IMPRIMIR_BASE");
	
?>